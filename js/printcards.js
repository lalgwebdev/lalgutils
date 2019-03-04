// Simplify the PDF printing page for Membership Cards
CRM.$(function($) {
	jQuery(document).ready(function(){
 
	  $('.crm-contact-task-pdf-form-block select#template').val('71').change();
	  $('#_qf_LalgPrintCards_upload-bottom').val('Download and clear flags');
	  $('#_qf_LalgPrintCards_submit_preview-bottom').val('Download');

	  $('.crm-contact-task-pdf-form-block table').hide();
	  setTimeout(() => $('.crm-contact-task-pdf-form-block div.crm-accordion-wrapper').hide(), 1000);
	});
});
