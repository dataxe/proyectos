// JavaScript Document
$(document).ready(function() {
	 // ambos procesaran en guardar_cargar_factura.php
	 // servira para editar los de tipo input text.
     $('.text').editable('guardar_cargar_factura.php');
	 // servira para editar el cuadro combinado de paises
	 $('.select').editable('guardar_cargar_factura.php', { 
		 data   : " {'1':'Argentina','2':'Bolivia','3':'Peru', '4':'Chile'}",
		 type   : 'select',
		 submit : 'OK'
	 });
	 // servira para editar el textarea.
	 $('.textarea').editable('guardar_cargar_factura.php', { 
		 type     : 'textarea',
		 submit   : 'OK'
	 });	 
 });