const container = document.querySelector('.content--box');


for(var i = 0; i < 30; i++) {
    const div = document.createElement('div');
    div.classList.add("content--box--inner")
    container.appendChild(div);
}

