$('.link').click(function(){
    var link = $(this).attr('data-link')
    window.open(link);
})

downloadXls = (fileArrayBuffer, filename) => {
    let data = new Blob(fileArrayBuffer, {type: 'application/vnd.ms-excel,charset=utf-8'});
    if (typeof window.chrome !== 'undefined') {
        // Chrome
        var link = document.createElement('a');
        link.href = window.URL.createObjectURL(data);
        link.download = filename;
        link.click();
    } else if (typeof window.navigator.msSaveBlob !== 'undefined') {
        // IE
        var blob = new Blob([data], {type: 'application/force-download'});
        window.navigator.msSaveBlob(blob, filename);
    } else {
        // Firefox
        var file = new File([data], filename, {type: 'application/force-download'});
        window.open(URL.createObjectURL(file));
    }
}