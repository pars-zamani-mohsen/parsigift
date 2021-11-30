/**
* Zamani list js code
* src="{{ asset('manager/assets/js/list.js')}}"
*/

var deleteElements = document.querySelectorAll('._delete');
for (i = 0; i < deleteElements.length; i++) {
    deleteElements[i].addEventListener('click', (e) => {
        Swal.fire({
            title: 'حذف',
            text: "از حذف رکورد مورد نظر اطمینان دارید؟",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#435ebe',
            cancelButtonColor: '#dc3545',
            cancelButtonText: 'خیر، مطمئن نیستم!',
            confirmButtonText: 'بلی',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = e.target.attributes[1].nodeValue;
            }
        })
    })
}

var closeElements = document.querySelectorAll('._deactive');
for (i = 0; i < closeElements.length; i++) {
    closeElements[i].addEventListener('click', (e) => {
        Swal.fire({
            title: 'فعال/غیر فعال سازی',
            text: "از فعال/غیر فعال سازی گزینه مورد نظر اطمینان دارید؟",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#435ebe',
            cancelButtonColor: '#dc3545',
            cancelButtonText: 'خیر',
            confirmButtonText: 'بلی',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = e.target.attributes[1].nodeValue;
            }
        })
    })
}
