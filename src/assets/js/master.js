const swalWithBootstrapButtons = Swal.mixin({
	customClass: {
		confirmButton: 'btn btn-success',
	    cancelButton: 'btn btn-danger'
	},
	buttonsStyling: false,
})

function loadingShow() {
	$("#cover-spin").show();
}

function loadingHide() {
	$("#cover-spin").hide();
}

function messageSuccess(title, message) {
	swalWithBootstrapButtons.fire(
      title,
      message,
      'success'
    )
}

function messageError(title, message) {
	swalWithBootstrapButtons.fire(
      title,
      message,
      'error'
    )
}

(function($){
	"use strict"
	$.fn.openSelect = function() {
		return this.each(function(idx, domEl){
			if (document.createEvent) {
				var event = document.createEvent("MouseEvents");
				event.initMouseEvent("mousedown", true, true, window, 0,0,0,0,0,false,false,false,false,0,null);
				domEl.dispatchEvent(event);
			} else if (element.fireEvent) {
				domEl.fireEvent("onmousedown");
			}
		});
	}
}(jQuery));

// 			swalWithBootstrapButtons.fire({
			  // title: 'Are you sure?',
			  // text: "You won't be able to revert this!",
			  // type: 'warning',
			  // showCancelButton: true,
			  // confirmButtonText: 'Yes, delete it!',
			  // cancelButtonText: 'No, cancel!',
// 			  reverseButtons: true
// 			}).then((result) => {
// 			  if (result.value) {
			    // swalWithBootstrapButtons.fire(
			    //   'Deleted!',
			    //   'Your file has been deleted.',
			    //   'success'
			    // )
// 			  } else if (
// 			    // Read more about handling dismissals
// 			    result.dismiss === Swal.DismissReason.cancel
// 			  ) {
// 			    swalWithBootstrapButtons.fire(
// 			      'Cancelled',
// 			      'Your imaginary file is safe :)',
// 			      'error'
// 			    )
// 			  }
// 			})