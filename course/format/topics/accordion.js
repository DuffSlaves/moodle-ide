var acc = document.getElementsByClassName("sectionname");
var i;

for (i = 0; i < acc.length; i++) {
    acc[i].style.marginBottom = 0;
    acc[i].addEventListener('click', function() {
        this.classList.toggle("active");
        this.nextElementSibling.classList.toggle('display');
        this.nextElementSibling.nextElementSibling.classList.toggle('display');
    });
}