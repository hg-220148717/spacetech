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