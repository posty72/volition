var searchText = document.getElementById("searchInput");

if (searchText.value == "Search...") {
  searchText.style.color = "#aaa";
}

searchText.onfocus = function () {
  if (searchText.value == "Search...") {
    searchText.value = "";
    searchText.style.color = "#000";
  }
};
