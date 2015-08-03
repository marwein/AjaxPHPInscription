$(document).ready(function () {
    function randomNumber(min, max) {
		return Math.floor(Math.random() * (max - min + 1) + min);
	};
	
	function now() {
		var today = new Date();
		var jj = today.getDate();
		var mm = today.getMonth()+1; //janvier est le mois 0!
		var aaaa = today.getFullYear();

		if(jj<10) {
			jj='0'+jj
		} 

		if(mm<10) {
			mm='0'+mm
		} 

		today = jj+'/'+mm+'/'+aaaa;
		return (today);	
	};
	
	
	$('#category').select2();
	$('#captchaOperation').html([randomNumber(1, randomNumber(10, 30)), '+', randomNumber(1, randomNumber(10, 30))].join(' '));
	
	$('#birthDay').datepicker({
	    format: "dd/mm/yyyy",
		startDate: "01/01/1900",
		endDate: "today",
		startView: 2,
		clearBtn: true,
		autoclose: true
	})
	.on('changeDate', function(e) {
		// Revalidate the date field
		$('#userForm').formValidation('revalidateField', 'birthDay');
	});
	
	$("#phoneform").intlTelInput({
		utilsScript: "http://jackocnr.com/lib/intl-tel-input/lib/libphonenumber/build/utils.js?2",
		autoPlaceholder: true,
		preferredCountries: [ "tn", "dz", "ma", "it", "fr", "us", "gb" ],
	});
	
	//FormValidation
    $('#userForm')
	.find('[name="colors"]')
		.select2()
		// Revalidate the color when it is changed
		.change(function(e) {
			$('#userForm').formValidation('revalidateField', 'category');
		})
		.end()
    .formValidation({
        framework: 'bootstrap',
        icon: {
            valid: 'fa fa-check fa-2x',
            invalid: 'fa fa-remove fa-2x',
            validating: 'fa fa-refresh fa-2x'
        },
        fields: {
            firstname: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                    stringLength: {
                        min: 3,
                        max: 20,
                        message: '3 to 20 characters max'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z ]{3,20}$/,
                        message: 'Only Alpha-numeric are accepted'
                    }
                }
            },
            lastname: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                    stringLength: {
                        min: 3,
                        max: 20,
                        message: '3 to 20 characters'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z ]{3,20}$/,
                        message: 'Only Alpha-numeric and whitespaces are accepted'
                    }
                }
            },
            pseudo: {
				threshold: 6,
				validators: {
					notEmpty: {
						message: 'Obligatoire'
					},
					stringLength: {
						min: 6,
						max: 15,
						message: '6 to 15 characters'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9]{6,15}$/,
						message: 'Alpha-numeric only'
					},
					// Verification si le pseudo est dispo ou pas
					remote: {
						message: 'This pseudo is already used',
						url: 'pseudo.php',
						data: {
							type: 'pseudo'
						},
						type: 'POST'
					}

				}
			},
            email: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                    regexp: {
                        regexp: /^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i,
                        message: 'Invalid mail adress'
                    },
					remote: {
						message: 'this email is registred',
						url: 'mail.php',
						data: {
							type: 'email'
						},
						type: 'POST'
					}
                }
            },
            pass: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                    stringLength: {
                        min: 8,
                        max: 20,
                        message: '8 to 20  characters'
                    }
                }
            },
            confpass: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                    identical: {
                        field: 'pass',
                        message: 'The password and its confirmation are not the same'
                    }
                }
            },
			phoneform: {
                validators: {
                    callback: {
						message: 'Invalid Number',
						callback: function(value, validator, $field) {
							var isValid = $field.intlTelInput("isValidNumber");
							return (isValid);
						}
					}
                }
            },
			skype: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    }
                }
            },
			category: {
				validators: {
					callback: {
						message: 'Required',
						callback: function(value, validator, $field) {
							// Get the selected options
							var options = validator.getFieldElements('category').val();
							return (options != null);
						}
					}
				}
			},
			birthDay: {
                validators: {
                    notEmpty: {
                        message: 'Required'
                    },
                    date: {
                        format: 'DD/MM/YYYY',
                        min: '01/01/1900',
                        max: now(),
                        message: 'The date is not a valid'
                    }
                }
            },
			captcha: {
				validators: {
					callback: {
						message: 'Wrong answer',
						callback: function(value, validator, $field) {
							var items = $('#captchaOperation').html().split(' '), sum = parseInt(items[0]) + parseInt(items[2]);
							return value == sum;
						}
					}
				}
			}
        }
    })
    .on('success.form.fv', function(e) {
        // Prevent form submission
        e.preventDefault();
		var phoneNumber = $("#phoneform").intlTelInput("getExtension") + $("#phoneform").intlTelInput("getNumber");
		$("#phone").val(phoneNumber);
        var $form = $(e.target),
            fv    = $form.data('formValidation');
        var submit = $('#envoyer');  // submit button
        submit.html('<i class="fa fa-refresh fa-refresh-animate"></i> Processing');
        $.ajax({
            url: './inscription.php', // form action url
            type: 'POST', // form submit method get/post
            dataType: 'html', // request type html/json/xml
            data: $form.serialize(),
            success: function(result) {
                var $message;
				var $boutton;
                if (result) {
                    $message = '<div class="alert alert-success col-xs-12 col-md-4 col-md-offset-4"><div class="row"><div class="col-xs-12 center"><i class="fa fa-check-circle fa-2x"></i> <strong>OK!</strong> vous &ecirc;tes enregistr&eacute;<br/>normalement on envoi le mail de verification</div></div></div>';
					$boutton = '<i class="fa fa-check"></i> Done';
                }
                else {
                    $message = '<div class="alert alert-danger col-xs-12 col-md-4 col-md-offset-4"><div class="row"><div class="col-xs-12 center"><i class="fa fa-times-circle fa-2x"></i> <strong>Oops!</strong> un petit probl&egrave;me avec l\'ajout d\'utilisateur</div></div></div>';
					$boutton = '<i class="fa fa-close"></i> Error';
					submit.removeClass('btn-success');
					submit.addClass('btn-danger');
                }
				submit.html($boutton);
				$form.replaceWith($message).fadeIn(10000);
				$form.destroy();
                
            },
            error: function(e) {
                console.log(e)
            }
        });
    });
});