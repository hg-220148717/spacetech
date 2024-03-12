window.onload = () => {
    filterProduct("All");
};

function filterProduct(value) {
    console.log("Filtering products with value:", value);
    let buttons = document.querySelectorAll(".button-value");
    buttons.forEach((button) => {
        if (value.toUpperCase() === button.innerText.toUpperCase()) {
            button.classList.add("active");
        } else {
            button.classList.remove("active");
        }
    });

    let elements = document.querySelectorAll(".pro");

    elements.forEach((element) => {
        if (value === "All" || element.classList.contains(value)) {
            element.classList.remove("hide");
            productsFound = true;
        } else {
            element.classList.add("hide");
        }
    });

}

document.getElementById("Search").addEventListener("click", () => {
    console.log("Search button clicked");
    let searchInput = document.getElementById("search-input").value.toUpperCase();
    let elements = document.querySelectorAll(".description");
    let cards = document.querySelectorAll(".pro");
    elements.forEach((element, index) => {
        if (element.innerText.toUpperCase().includes(searchInput)) {
            cards[index].classList.remove("hide");
        } else {
            cards[index].classList.add("hide");
        }
    });
});

document.querySelectorAll(".button-value").forEach(button => {
    button.addEventListener("click", () => {
        console.log("Button clicked:", button.innerText);
        filterProduct(button.innerText);
    });
});
