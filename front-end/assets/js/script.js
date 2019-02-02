

var host = 'http://127.0.0.1/priscila/';


$('#btnAcao').click(function(e) {
    e.preventDefault();
    var valorcode = $('#codigoAcao').val();
    var alvo = $("#localList");
    alvo.empty();
    if(valorcode.trim().length > 0)
    {
        $.ajax({
            type: 'GET',
            url: host + 'cod/' + valorcode.trim(),
            dataType: 'html',
            success: function(data) {
                try {
                    var list = JSON.parse(data);
                    
                    if(list.ok)
                    {
                        var table = $("<table></table>");
                        var inteirar = list.dados;
                        $.each(inteirar, function(idx, stock) {

                          var row = $("<tr></tr>");

                          var link = '<a href="' + host + 'Stock/drawnew/' + stock.id + '">' + stock.cod + '</a> ' +
                                     '<a href="' + host + 'User/fadd/' + stock.id + '">' + 'A' + '</a>';
                          row.append($("<td></td>").html(link));
                          table.append(row);
                        });

                        // insert the table somewhere in your dom
                        alvo.append(table); 
                    }
                    
                } catch (e) {
                    console.error(e);
                }
            },
            error: function(e, motivo){                
                    console.log("houve uma flha \n" + e + "motivo: " + motivo);
                    console.log(JSON.stringify(e, null, 4));
            } 
        }); 
    }
});

