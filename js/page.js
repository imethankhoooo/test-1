const list = document.querySelectorAll('.list');
function activeLink() {
    list.forEach((item) =>
        item.classList.remove('active'));
    this.classList.add('active');

}

list.forEach(item => item.addEventListener('click', activeLink));

let menuToggle = document.querySelector('.menuToggle');
let navigation = document.querySelector('.navigation');
let Page = document.querySelector('.Page');
menuToggle.addEventListener('click', function () {
    navigation.classList.toggle('active');
    document.querySelectorAll('.Page').forEach(page => page.classList.toggle('active'))
});
let verityPage = 0; 

function switchPage(currentPage) {
    if (verityPage === 0) {
        verityPage = 1; 

    
        document.querySelectorAll('.Page').forEach(page => page.classList.add('hidden'));
        setTimeout(() => {
            
            document.querySelector(`.${currentPage}`).classList.remove('hidden');
            verityPage = 0;  
        }, 500);
    } else {
        setTimeout(() => {
            switchPage(currentPage);
        }, 15);
    }
}
