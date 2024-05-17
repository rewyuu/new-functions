'use strict';



/**
 * navbar toggle
 */

const navbar = document.querySelector("[data-navbar]");
const navbarLinks = document.querySelectorAll("[data-nav-link]");
const menuToggleBtn = document.querySelector("[data-menu-toggle-btn]");

menuToggleBtn.addEventListener("click", function () {
  navbar.classList.toggle("active");
  this.classList.toggle("active");
});

for (let i = 0; i < navbarLinks.length; i++) {
  navbarLinks[i].addEventListener("click", function () {
    navbar.classList.toggle("active");
    menuToggleBtn.classList.toggle("active");
  });
}



/**
 * header sticky & back to top
 */

const header = document.querySelector("[data-header]");
const backTopBtn = document.querySelector("[data-back-top-btn]");

window.addEventListener("scroll", function () {
  if (window.scrollY >= 100) {
    header.classList.add("active");
    backTopBtn.classList.add("active");
  } else {
    header.classList.remove("active");
    backTopBtn.classList.remove("active");
  }
});

let cartItemCount = localStorage.getItem('cartItemCount') ? parseInt(localStorage.getItem('cartItemCount')) : 0;
  document.getElementById('cart-button').innerText = `Cart Items: ${cartItemCount}`;

  function redirectToCart() {
    window.location.href = "cart.php";
  }

  function redirectToLogout() {

    localStorage.removeItem('cartItemCount');
    localStorage.removeItem('cartItems');
    window.location.href = "logout.php"; 
  }

  function addToCart(itemName, itemPrice) {
    cartItemCount++;
    localStorage.setItem('cartItemCount', cartItemCount);
    document.getElementById('cart-button').innerText = `Cart Items: ${cartItemCount}`;

    let cartItems = localStorage.getItem('cartItems') ? JSON.parse(localStorage.getItem('cartItems')) : [];
    cartItems.push({ name: itemName, price: itemPrice });
    localStorage.setItem('cartItems', JSON.stringify(cartItems));

    fetch('add_to_cart.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ name: itemName, price: itemPrice })
    }).then(response => response.text())
      .then(data => {
        console.log('Item added to cart server-side');
      });

    let cartMessage = event.target.closest('.food-menu-card').querySelector('.cart-message');
    cartMessage.innerText = "Item added to cart!";
    cartMessage.style.visibility = 'visible';
    setTimeout(function() {
      cartMessage.style.visibility = 'hidden';
    }, 2000);
  }

  function scrollToFoodMenu() {
    document.getElementById('food-menu').scrollIntoView({ behavior: 'smooth' });
  }

  function scrollToFoodMenu() {
    document.getElementById('food-menu').scrollIntoView({ behavior: 'smooth' });
  }

  function addToCart(name, price) {
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

        const newItem = {
            id: Date.now().toString(),
            name: name,
            price: price
        };

        cartItems.push(newItem);
        localStorage.setItem('cartItems', JSON.stringify(cartItems));

        let cartItemCount = localStorage.getItem('cartItemCount') ? parseInt(localStorage.getItem('cartItemCount')) : 0;
        cartItemCount++;
        localStorage.setItem('cartItemCount', cartItemCount);
        document.getElementById('cart-button').innerText = `Cart Items: ${cartItemCount}`;
      }