/*! 
* @Author  Saikat Mahapatra
* @Support 
* @Email   <mahapatra.saikat29@gmail.com>
* @version 3.1.4
* @repository https://github.com/saikatmahapatra/ci-demo-app.git
* @license MIT <http://opensource.org/licenses/MIT>
*/

if (typeof jQuery === 'undefined') {
    throw new Error('CI App requires jQuery')
}

//-----------------------------------------------------------//
// Main App 
//-----------------------------------------------------------//
var UI = function() {
    this.closeMessageAlert = false;
    this.closeUIAlerts = function() {
        if (this.closeMessageAlert) {
            $('.auto-closable-alert').fadeTo(5000, 500).slideUp(500, function() {
                $('.auto-closable-alert').alert('clsoe');
            });
        }
    };
    
    this.numericOnly = function(event, obj) {
        obj.val(obj.val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || obj.val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    };
    this.askConfirmation = function(event) {
        //event.preventDefault();
        var result = false;
        var that = $(this);
        var isConfirmationRequired = that.attr('data-confirmation');
        var confirmationMessage = that.attr('data-confirmation-message');
        var confirmed = confirm(confirmationMessage);
        if (confirmed) {
            result = true;
        } else {
            result = false;
        }
        return result;
    };
    this.scrollToTop = function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 500) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        });
        $('.scrollup').click(function() {
            $("html, body").animate({
                scrollTop: 0
            }, 300);
            return false;
        });
	};

	this.toggleIcon=function(e) {
		$(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('glyphicon-plus glyphicon-minus');
	};
	
	this.renderFieldHelp = function(e){
		$('.form-control').on('focusin',function(e){
			var elm = $(this);
			var helpText = elm.attr('data-hint');
			if(typeof helpText != 'undefined' && helpText.length > 0){
				var html = '<div class="info-help">';
				html+=elm.attr('data-hint');
				html+='</div>';
				elm.after(html);
			}
		});
		$('.form-control').on('focusout',function(e){
			var elm = $(this);
			var helpText = elm.attr('data-hint');;
			if(typeof helpText != 'undefined' && helpText.length > 0){				
				elm.next().remove();
			}
		});
	};
};
var app = new UI(); // create app object



//-----------------------------------------------------------//
// Document Ready Event Handler
//-----------------------------------------------------------//
$(document).ready(initPage);

function initPage() {
    // Alert message close after few sec
    app.closeMessageAlert = false;
    app.closeUIAlerts();
	app.scrollToTop();
	panelGroupToggle();
	app.renderFieldHelp();
}





//-----------------------------------------------------------//
// Document Interaction Handler
//-----------------------------------------------------------//
$(document).on('keypress keyup blur', '.numeric-decimal', app.numericOnly);
$(document).on('click', '.btn-delete', app.askConfirmation);

function panelGroupToggle(){
	$('.panel-group').on('hidden.bs.collapse', app.toggleIcon);
	$('.panel-group').on('shown.bs.collapse', app.toggleIcon);
}