import * as bootstrap from 'bootstrap'
export var  showToast = function(text){
    const customToast = document.getElementById('customToast')
    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(customToast)
    if(text){
        $('#toast-body').text(text)
    }
    toastBootstrap.show()
}