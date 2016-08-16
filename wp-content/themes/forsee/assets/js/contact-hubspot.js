hbspt.forms.create({ 
    portalId: '493485',
    formId: '959e731e-1421-40ed-83eb-e3dc4992ddba',
    target: '.contact-overlay-form',
	onFormReady: function($form) {
		$('input[name="career_contact_me_about_course"]').val(hsCourse).change();
		$('input[name="email"]').val(hsEmail).change();
		$('input[name="course_broker_name"]').val(hsBroker).change();	
		$('input#career_contact_me_opt_in-959e731e-1421-40ed-83eb-e3dc4992ddba').parent().find('span:first').html(checkIn);		
	}     
});