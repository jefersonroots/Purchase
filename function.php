
 <script languege=javascript>
 function somenteNumeros(e) {
    var charCode = e.charCode ? e.charCode : e.keyCode;
    // charCode 8 = backspace   
    // charCode 9 = tab
   if (charCode != 8 && charCode != 9) {
       // charCode 48 equivale a 0   
       // charCode 57 equivale a 9
       var max = 8;
       var pedido = document.getElementById('pedido');           
            
       if ((charCode < 48 || charCode > 57)||(pedido.value.length >= max)) {
          return false;
       }
       
    }
}
</script>