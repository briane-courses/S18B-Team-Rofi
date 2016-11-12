<?php

/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 11/6/2016
 * Time: 7:07 PM
 */
class Admin_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getMinuteInterval() {
        return 15; // TODO retrieve from Settings
    }

    public function getMaxNumberOfSlots() {
        return 4; // TODO retrieve from Settings
    }

    public function getMinimumHour() { // TODO parameter must be department ID
        return 6; // TODO retrieve value depending on department
    }

    public function getMaximumHour() { // TODO parameter must be department ID
        return 20; // TODO retrieve value depending on department
    }

    public function getTimes($first_hour, $first_minute, $minute_interval, $minimum_hour, $maximum_hour, $tomorrow) {
        $times = array();
        $startMinute = 0;
        $daysForward = 0;

        if (!$tomorrow)
            $startMinute = intval($first_minute / $minute_interval) * $minute_interval; // calculate first_minute to suit current time
        else
            $daysForward++; // plus 1 to day if tomorrow is true

        if ($first_hour < $minimum_hour || $first_hour == null) // set to minimum_hour if first_hour is below the minimum_hour or if first_hour is null
            $first_hour = $minimum_hour;

        for ($hour = $first_hour; $hour < $maximum_hour ; $hour++) {
            for ($minute = $startMinute; $minute < 60; $minute += $minute_interval) {

                $time = mktime($hour, $minute, 0, date("m"), date("d") + $daysForward, date("Y"));

                $times[] = $time;

            }

            $startMinute = 0; // reset to 0 to suit the succeeding hours
        }

        $times[] = mktime($hour, 0, 0, date("m"), date("d") + $daysForward, date("Y"));

        return $times;
    }

    function queryAllAdministators() {
        $this->db->select('*');
        $this->db->from(TABLE_ADMINISTRATORS);
        $this->db->join(TABLE_DEPARTMENTS, 'admin_departmentid = departmentid');
        $this->db->order_by(COLUMN_FIRST_NAME, COLUMN_LAST_NAME);
        $query = $this->db->get();
        return $query->result();
    }

    function queryAllModerators() {
        $this->db->select('*');
        $this->db->from(TABLE_MODERATORS);
        $this->db->join(TABLE_DEPARTMENTS, 'mod_departmentid = departmentid');
        $this->db->order_by(COLUMN_FIRST_NAME, COLUMN_LAST_NAME);
        $query = $this->db->get();
        return $query->result();
    }

    function queryAllRooms() {
        //return $this->db->get(TABLE_ROOMS)->result();
        $sql = "SELECT roomid, name, buildingid, departmentid, COUNT(computerid) as capacity
                FROM rooms NATURAL JOIN computers
                GROUP BY roomid
                ORDER BY name";
        return $this->db->query($sql)->result();
    }

    function queryAllComputers() {
        return $this->db->get(TABLE_COMPUTERS)->result();
    }

    function queryComputersAtRoomName($name) {
        $sql = "SELECT * 
             FROM computers NATURAL JOIN 
              (SELECT roomid
               FROM rooms
               WHERE name = ?) t1";
        return $this->db->query($sql, array($name))->result();
    }

    function queryComputersAtRoomID($id) {
        $sql = "SELECT * 
             FROM computers NATURAL JOIN 
              (SELECT roomid
               FROM rooms
               WHERE roomid = ?) t1";
        return $this->db->query($sql, array($id))->result();
    }

    function queryAllComputersAtBuildingID($id) {
        $sql = "SELECT * 
             FROM computers NATURAL JOIN 
              (SELECT roomid, name
               FROM rooms
               WHERE buildingid = ?) t1";
        return $this->db->query($sql, array($id))->result();
    }

    function queryComputersAtBuildingIDAndRoomID($bid,$id) {
        $sql = "SELECT * 
             FROM computers NATURAL JOIN 
              (SELECT roomid, name
               FROM rooms
               WHERE roomid = ? AND buildingid = ?) t1";
        return $this->db->query($sql, array($id, $bid))->result();
    }

    function queryAllBuildings() {
        return $this->db->get(TABLE_BUILDINGS)->result();
    }

    function queryAllRoomsAtBuildingID($id) {
        $sql = "SELECT * 
                FROM rooms NATURAL JOIN 
                  (SELECT buildingid
                   FROM buildings
                   WHERE buildingid = ?) b";
        return $this->db->query($sql, array($id))->result();
    }

    function queryAllRoomsAtBuildingName($name) {
        $sql = "SELECT * 
                FROM rooms NATURAL JOIN 
                  (SELECT buildingid
                   FROM buildings
                   WHERE name = ?) b";
        return $this->db->query($sql, array($name))->result();
    }

    function queryColleges() {
        return $this->db->get(TABLE_COLLEGES)->result();
    }

    function queryTypes() {
        return $this->db->get(TABLE_TYPES)->result();
    }

    function queryReservationsAtBuildingIDOnDate($id, $date) {
        $sql = "SELECT *
                FROM rooms r NATURAL JOIN 
                  (SELECT buildingid
                   FROM  buildings
                   WHERE buildingid = ?) b NATURAL JOIN 
                  computers NATURAL JOIN 
                  (SELECT *
                   FROM reservations
                   WHERE date = ?) r";
        return $this->db->query($sql, array($id, $date))->result();
    }

    function queryReservationsAtRoomIDOnDate($id, $date) {
        $sql = "SELECT *
                FROM (SELECT *
                      FROM reservations
                      WHERE date = ?) r NATURAL JOIN
                  computers NATURAL JOIN
                  (SELECT roomid
                   FROM rooms
                   WHERE roomid = ?) ro";
        return $this->db->query($sql, array($date, $id))->result();
    }

    function queryReservationsOfComputerIDOnDate($id, $date) {
        $sql = "SELECT *
                FROM (SELECT *
                      FROM reservations
                      WHERE date = ?) r NATURAL JOIN
                  (SELECT computerid
                   FROM computers
                   WHERE computerid = ?) c";
        return $this->db->query($sql, array($date, $id))->result();
    }

    function isValidUser($email, $pass) {



        $sql = "SELECT *
                      FROM administrators
                      WHERE email = ? AND password = ?";

        $result = $this->db->query($sql, array($email, $pass))->result();
        // If credentials is found.


        return count($result)>=1;
    }

    function queryAdminAccount($email) {
        $this->db->select("*");
        $this->db->from(TABLE_ADMINISTRATORS);
        $this->db->where(COLUMN_EMAIL, $email);
        $query = $this->db->get();

        return $query->row_array();
    }

    function queryLatestRoomID() {
        return $this->db->insert_id();
    }

    function insertRoomsAndComputers($data) {
        $rooms = $data['rooms'];

        foreach($rooms as $room) {
            if ($this->isExistingRoom($room[0]))
                continue;

            $insertRoomData = array(
                'name' => $room[0],
                'buildingid' => $data['buildingid'],
                'departmentid' => $data['departmentid']
            );

            $this->insertRoom($insertRoomData);

            $insertComputersData = array(
                'computerCount' => $room[1],
                'roomid' => $this->queryLatestRoomID(),
            );

            $this->insertComputersAtRoom($insertComputersData);
        }
    }

    function insertRoom($room) {
        $this->db->insert(TABLE_ROOMS, $room);
    }

    function insertComputersAtRoom($data) {
        $computerCount = $data['computerCount'];

        for ($i = 1; $i <= $computerCount; $i++) {
            $insertComputerData = array(
                'computerno' => $i,
                'roomid' => $data['roomid'],
            );
            $this->insertComputer($insertComputerData);
        }
    }

    function insertComputer($computer) {
        $this->db->insert(TABLE_COMPUTERS, $computer);
    }

    function isExistingRoom($roomName) {
        $this->db->select('*');
        $this->db->from(TABLE_ROOMS);
        $this->db->where(COLUMN_NAME, $roomName);
        $query = $this->db->get();
        $result = $query->result();

        return count($result)>=1;
    }
}