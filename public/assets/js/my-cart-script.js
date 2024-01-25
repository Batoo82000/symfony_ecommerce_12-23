window.onload = () => {
  const mainContent = document.querySelector(".main_content");
  const tbody = mainContent.querySelector("tbody");
  let cart = JSON.parse(mainContent.dataset.cart);
  const cartTotalAmounts = mainContent.querySelectorAll(".cart_total_amount");
  function formatPrice(price) {
    return Intl.NumberFormat("fr-FR", {
      style: "currency",
      currency: "EUR",
    }).format(price);
  }
  async function fetchData(requestUrl) {
    let response = await fetch(requestUrl);
    return await response.json();
  }
  function addEventListenerToLinks() {
    const links = tbody.querySelectorAll("a");
    links.forEach((link) => {
      link.addEventListener("click", manageLink);
    });
  }
  async function manageLink(event) {
    event.preventDefault();
    const link = event.target.href ? event.target : event.target.parentNode;
    const requestUrl = link.href;
    cart = await fetchData(requestUrl);
    initCart();
  }


  const initCart = () => {
    tbody.innerHTML = "";
    cart.items.forEach((item) => {
      const { product, quantity, sub_total } = item;
      const content = `<tr>
            <td class="product-thumbnail">
                <a href="/product/${product.slug}">
                    <img width="50" alt="${
                      product.name
                    }" src="/assets/images/products/${product.imagesUrls[0]}">
                </a>
            </td>
            <td data-title="Product" class="product-name">
                <a href="/product/${product.slug}">${product.name}</a>
            </td>
            <td data-title="Price" class="product-price">
            ${formatPrice(product.soldePrice / 100)}</td>
            <td data-title="Quantity" class="product-quantity">
                <div class="quantity">
                    <a href="/cart/remove/${product.id}">
                        <input type="button" value="-" class="minus">
                    </a>
                    <input type="text" name="quantity" value="${quantity}" title="Qty" size="4" class="qty">
                    <a href="/cart/add/${product.id}">
                        <input type="button" value="+" class="plus">
                    </a>
                </div>
            </td>
            <td data-title="Total" class="product-subtotal">
                ${formatPrice(sub_total / 100)}
            </td>
            <td data-title="Remove" class="product-remove">
                <a href="/cart/remove/${product.id}/${quantity}">
                    <i class="ti-close"></i>
                </a>
            </td>
        </tr>`;
      tbody.innerHTML += content;
    });

    cartTotalAmounts.forEach((cartTotalAmount) => {
      cartTotalAmount.innerHTML = formatPrice(cart.sub_total / 100);
    });
    addEventListenerToLinks();
  };
  initCart();
};
