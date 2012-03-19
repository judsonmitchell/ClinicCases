<?php

//script to add, update and delete contacts
session_start();
require('../auth/session_check.php');
require('../../../db.php');

//Get variables

$action = $_POST['action'];

if (isset($_POST['first_name']))
	{$first_name = $_POST['first_name'];}

if (isset($_POST['last_name']))
	{$last_name = $_POST['last_name'];}

if (isset($_POST['organization']))
	{$organization = $_POST['organization'];}

if (isset($_POST['contact_type']))
	{$contact_type = $_POST['contact_type'];}

if (isset($_POST['address']))
	{$address = $_POST['address'];}

if (isset($_POST['city']))
	{$city = $_POST['city'];}

if (isset($_POST['state']))
	{$state = $_POST['state'];}

if (isset($_POST['zip']))
	{$zip = $_POST['zip'];}

if (isset($_POST['url']))
	{$url = $_POST['url'];}

if (isset($_POST['notes']))
	{$notes = $_POST['notes'];}

if (isset($_POST['phone']))
	{$phone = $_POST['phone'];}

if (isset($_POST['email']))
	{$email = $_POST['email'];}

if (isset($_POST['case_id']))
	{$case_id = $_POST['case_id'];}

if (isset($_POST['id']))
	{$id = $_POST['id'];}

switch ($action) {
	case 'add':

		$add_contact = $dbh->prepare('INSERT INTO cm_contacts (id, first_name, last_name, organization, type, address, city, state, zip, phone, email, url, notes, assoc_case) VALUES (NULL, :first_name, :last_name, :organization, :contact_type, :address, :city, :state, :zip, :phone, :email, :url, :notes, :case_id);');

		$data = array('first_name' => $first_name, 'last_name' => $last_name, 'organization' => $organization, 'contact_type' => $contact_type, 'address' => $address, 'city' => $city, 'state' => $state, 'zip' => $zip, 'phone' => $phone, 'email' => $email, 'url' => $url, 'notes' => $notes, 'case_id' => $case_id);

		$add_contact->execute($data);

		$error = $add_contact->errorInfo();

		break;

	case 'edit':

		$edit_contact = $dbh->prepare("UPDATE cm_contacts SET first_name = :first_name, last_name = :last_name, organization = :organization, type = :contact_type, address = :address, city = :city, state = :state, zip = :zip, phone = :phone, email = :email, url = :url, notes = :notes, assoc_case = :case_id WHERE id = :id");

		$data = array('first_name' => $first_name, 'last_name' => $last_name, 'organization' => $organization, 'contact_type' => $contact_type, 'address' => $address, 'city' => $city, 'state' => $state, 'zip' => $zip, 'phone' => $phone, 'email' => $email, 'url' => $url, 'notes' => $notes, 'case_id' => $case_id, 'id' => $id);

		$edit_contact->execute($data);

		$error = $edit_contact->errorInfo();

		break;

	case 'delete':

		$delete_contact = $dbh->prepare("DELETE FROM cm_contacts WHERE id = :id");

		$data = array('id' => $id);

		$delete_contact->execute($data);

		$error = $delete_contact->errorInfo();

		break;

}

if($error[1])

		{
			$return = array('message' => 'Sorry, there was an error. Please try again.','error' => true);
			echo json_encode($return);
		}

		else
		{

			switch($action){
			case "add":
			$return = array('message'=>'Contact Added');
			echo json_encode($return);
			break;

			case "edit":
			$return = array('message'=>'Contact Edited','id' => $id);
			echo json_encode($return);
			break;

			case "delete":
			$return = array('message'=>'Contact Deleted');
			echo json_encode($return);
			break;

			}

		}
