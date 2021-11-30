/**
* Zamani custom js code
* src="{{ asset('manager/assets/js/custom.js')}}"
*/
document.getElementById('_logout').addEventListener('click', (e) => {
    Swal.fire({
        title: 'خروج',
        text: "از حساب کاربری خود خارج می شوید؟",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#435ebe',
        cancelButtonColor: '#dc3545',
        cancelButtonText: 'خیر',
        confirmButtonText: 'بلی، خارج می شوم',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/logout";
        }
    })
})

menuActivator();
function menuActivator() {
    var _node = document.querySelector("[data-url='"+window.location.pathname+"']");
    if (_node) {
        _node.classList.add('active');
        if (_node.classList[0] === 'submenu-item') {
            var _parent = _node.parentElement;
            _parent.classList.add('d-block')
        }
    }
}

