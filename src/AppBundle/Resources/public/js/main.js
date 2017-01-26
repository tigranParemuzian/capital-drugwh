/**
 * Created by tigran on 1/27/17.
 */

getDataTerminals();
const GET = 'GET';
const POST = 'POST';
var slugItem;
function addByBag(getId, state, slug){
    if(typeof slug=='undefined'){
        slug = slugItem
    }
//        console.log(slug);
    var id=0, count;
    if(state == 0){
        id = parseInt(getId.replace(/add_/g, ''));
    } else if (state == 1){
        id = parseInt(getId.replace(/remove_/g, ''));
    }

    var my_input = $('#used_count_'+id);
    my_input.css({'border': "2px inset"}).closest('.grid-column-buy_count').children('div.help-text').html('');
    if(state == 1){
        count = 0;
    }else {

        count = my_input.val();
    }
//            slug = $('#slug_'+id).val();
    var data = {'slug': slug, 'count':count};
    sendRequest(data, POST);
    $('#add_'+id).hide();
//        }
}

function updateData(getId, status){

    var id = parseInt(getId.replace(/used_count_/g, '')), slug, count;

    var my_input = $('#used_count_'+id);
    if(my_input.val()<=0 && status == 0){
        my_input.css({'border': "red solid 1px"}).closest('.grid-column-buy_count').children('div.help-text').html('<span id="helpBlock" class="help-block" style="color: red">Please add count.</span>')
        $('#add_'+id).show();
    }else {
        var price = my_input.closest('tr').children('td.grid-column-price').text();
        count = my_input.val();
        var totl_itom = parseFloat(price) * count;
        if(status != 1){
            $('#add_'+id).show();
            my_input.css({'border': "2px inset"}).closest('.grid-column-buy_count').children('div.help-text').html('<b>'+ totl_itom +'$</b>');
        }else {
            my_input.css({'border': "2px inset"}).closest('.grid-column-buy_count').children('div.help-text').html('');
        }
    }
}

function writeOrder(orders){
    var unt;
    switch (orders.product.product_item.unit){
        case 0:
            unt = ' ct';
            break;
        case 1:
            unt = ' ml';
            break;
        case 2:
            unt = ' s ea';
            break;
        case 3:
            unt = ' gm';
            break;
        default:
            break;

    }

    slugItem = orders.product.slug;

    $('table.order>tbody').append('<tr> <td >'+ orders.product.name +'</td>' +
        '<td>' + orders.product.product_item.size + ' ' + unt +'</td>' +
        '<td>' + orders.product.price +' $</td>'+
        '<td>' + orders.count +' </td>'+
        '<td>' + orders.sub_total +' $</td>'+
        '<td> <button id="remove_' + orders.product.id +'" onclick="addByBag(this.id, 1)" class="btn btn-danger">' +
        '<span class="glyphicon glyphicon glyphicon-remove"></span>' +
        '</button>' +
        '</td>' +
        '</tr>')

}
/**
 * This function use to get data
 */
function getDataTerminals() {

    jQuery.ajax({
        url: "/api/bag/my/bag",
        type: "GET",
        contentType: 'application/json; charset=utf-8',
        async: true,
        success: function (resultData) {
            var result = resultData, counts;
            var total = 0;

            $('table.order>tbody').html('');
            for (var i = 0; i < result.length; i++)
            {
                total += parseFloat(result[i].sub_total);
                $('#used_count_'+result[i].product.id).val(result[i].count);
                writeOrder(result[i]);
            }

            $('.total-count').html('<span>Items Count </span> '+result.length);
            $('.total-price').html('<span>Total price </span>' + total + ' $');
            $('.selected_infos').html('My&nbsp;Bag&nbsp;<span class="badge">'+result.length+'</span>')
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus, errorThrown);
        }
    });
}

/**
 *
 * @param data
 * @param method
 */
function sendRequest(data, method)
{
    jQuery.ajax({
        url: '/api/bags/datas',
        type: "POST",
        contentType: 'application/json; charset=utf-8',
        async: true,
        dataType:"json",

        data: JSON.stringify(data),
        success:function(ansvwe)
        {
            getDataTerminals();

        }
    });
}