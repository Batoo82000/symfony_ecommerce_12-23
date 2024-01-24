
// Code JavaScript pour mélanger les produits dans chaque liste
document.addEventListener("DOMContentLoaded", function() { // Attend que le DOM soit entièrement chargé avant d'exécuter le code JavaScript.
    let randomProductsLists = document.querySelectorAll('.random-list');//Sélectionne toutes les listes de produits aléatoires

    randomProductsLists.forEach(function(list) { // itère sur chaque liste
        let products = Array.from(list.children); // Convertit les enfants de la liste en un tableau pour faciliter le mélange.
        shuffleArray(products); // Appelle la fonction shuffleArray pour mélanger le tableau de produits.

        
        list.innerHTML = ''; // Vide la liste existante et ajouter les produits mélangés
        products.forEach(function(product) { // itère sur chaque produit mélangé.
            list.appendChild(product); // Ajoute chaque produit mélangé à la liste.
        });
    });

    // Fonction pour mélanger un tableau (algorithme de Fisher-Yates)
    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) { // Itère du dernier élément au premier dans le tableau.
            let j = Math.floor(Math.random() * (i + 1)); // Génère un indice aléatoire.
            let temp = array[i]; // Échange les éléments d'indice i et j pour les mélanger.
            array[i] = array[j];
            array[j] = temp;
        }
    }
});
