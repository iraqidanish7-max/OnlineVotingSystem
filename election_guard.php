<?php
// election_guard.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/connection.php';

/*
  election_settings table:
  id | status | published_at
  status = 'draft'     (voting OPEN)
  status = 'published' (voting CLOSED)
*/

$electionStatus = 'draft'; // safe default (open)

$res = $conn->query("
    SELECT status
    FROM election_settings
    WHERE id = 1
    LIMIT 1
");

if ($res && $row = $res->fetch_assoc()) {
    $electionStatus = $row['status'];
}

$GLOBALS['ELECTION_STATUS'] = $electionStatus;