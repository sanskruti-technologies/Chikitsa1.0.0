<!DOCTYPE html>
<!--
	This file is part of Chikitsa.

    Chikitsa is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Chikitsa is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Chikitsa.  If not, see <https://www.gnu.org/licenses/>.
-->
<html lang="en">
    <head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Free & Open Source Software Chikitsa, Patient Management System is for Clinics and Hospital. Create Appointments , Maintain Patietn Records and Generate Bills.">
		<meta name="author" content="Sanskruti Technologies">

		<link rel="shortcut icon"  href="<?= base_url() ?>/favicon.ico"/>

		<title><?= $clinic['clinic_name'] .' - ' .$clinic['tag_line'];?></title>
        <!-- Custom fonts for this template -->
	    <link href="<?= base_url() ?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	    <!--<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">-->

		<!-- Custom styles for this template -->
		<link href="<?= base_url() ?>assets/css/sb-admin-2.min.css" rel="stylesheet">
		
		<!-- Custom styles for this page -->
		<link href="<?= base_url() ?>assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
		<link href="<?= base_url() ?>assets/vendor/datatables/responsive.dataTables.min.css" rel="stylesheet">
		
		<!-- Schedule Master CSS -->
		<!--link href="<?= base_url() ?>assets/vendor/schedule-template-master/css/style.css" rel="stylesheet"-->

		<!-- Bootstrap core JavaScript-->
		<script src="<?= base_url() ?>assets/vendor/jquery/jquery-1.11.3.min.js"></script>
    	<script src="<?= base_url() ?>assets/vendor/jquery/jquery-ui.min.js"></script>
		<script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
	
		<!-- Core plugin JavaScript-->
		<script src="<?= base_url() ?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>

		<!-- autocomplete -->
		<link href="<?= base_url() ?>assets/vendor/css/jquery-ui-1.9.1.custom.min.css" rel="stylesheet">
	
		<!-- Page level custom scripts -->
		<script src="<?= base_url() ?>assets/js/demo/datatables-demo.js"></script>
	    <!-- Custom scripts for all pages-->
	    <script src="<?= base_url() ?>assets/js/sb-admin-2.js"></script>
		<!-- CHOSEN SCRIPTS-->
		<script src="<?= base_url() ?>assets/vendor/chosen/chosen.jquery.min.js"></script>
		<link href="<?= base_url() ?>assets/vendor/chosen/chosen.min.css" rel="stylesheet">
		
	    <link href="<?= base_url() ?>assets/css/chikitsa.css" rel="stylesheet">
	
		<!-- Page level plugins -->
		<script src="<?= base_url() ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
		<script src="<?= base_url() ?>assets/vendor/datatables/dataTables.responsive.min.js"></script>
		<script src="<?= base_url() ?>assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

		   <!-- Datetime Picker -->
	    <link href="<?= base_url() ?>assets/vendor/datetimepicker/jquery.datetimepicker.min.css" rel="stylesheet">
	    <script src="<?= base_url() ?>assets/vendor/datetimepicker/jquery.datetimepicker.min.js"></script>
	    <script src="<?= base_url() ?>assets/vendor/scheduler/js/moment.js" ></script>

		<script src="<?= base_url() ?>assets/vendor/schedule-template-master/js/util.js"></script> <!-- util functions included in the CodyHouse framework -->
		<script src="<?= base_url() ?>assets/vendor/schedule-template-master/js/main.js"></script>

	</head>
	<body id="page-top">
		<!-- Page Wrapper -->
		<div id="wrapper">
