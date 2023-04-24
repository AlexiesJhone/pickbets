$(document).ready(function () {
  });
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td0 = tr[i].getElementsByTagName("td")[0];
    td = tr[i].getElementsByTagName("td")[2];
    td1 = tr[i].getElementsByTagName("td")[6];
    if (td) {
      txtValue = td.textContent || td.innerText;
      txtValue1 = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if (txtValue1.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
      }
       else {
        tr[i].style.display = "none";
      }
    }
  }
}
