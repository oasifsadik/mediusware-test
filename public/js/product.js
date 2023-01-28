var currentIndex = 0;

var indexs = [];

$(document).ready(function() {
    addVariantTemplate();
    $("#file-upload").dropzone({
        url: "{{ route('file-upload') }}",
        method: "post",
        addRemoveLinks: true,
        success: function(file, response) {
            console.log(file, response);
        },
        error: function(file, response) {
            //
            console.log(file.dataURL);
        }
    });
});

function addVariant(event) {
    event.preventDefault();
    addVariantTemplate();
}

function getCombination(arr, pre) {

    pre = pre || '';

    if (!arr.length) {
        return pre;
    }

    return arr[0].reduce(function(ans, value) {
        return ans.concat(getCombination(arr.slice(1), pre + value + '/'));
    }, []);
}

function updateVariantPreview() {

    var valueArray = [];

    $(".select2-value").each(function() {
        valueArray.push($(this).val());
    });

    var variantPreviewArray = getCombination(valueArray);


    var tableBody = '';

    $(variantPreviewArray).each(function(index, element) {
        tableBody += `<tr>
                        <th>
                                        <input type="hidden" name="product_preview[${index}][variant]" value="${element}">
                                        <span class="font-weight-bold">${element}</span>
                                    </th>
                        <td>
                                        <input type="text" class="form-control" value="0" name="product_preview[${index}][price]" required>
                                    </td>
                        <td>
                                        <input type="text" class="form-control" value="0" name="product_preview[${index}][stock]">
                                    </td>
                      </tr>`;
    });

    $("#variant-previews").empty().append(tableBody);
}



function removeVariant(event, element) {

    event.preventDefault();

    var jqElement = $(element);

    var position = indexs.indexOf(jqElement.data('index'))

    indexs.splice(position, 1)

    jqElement.parent().parent().parent().parent().remove();

    if (indexs.length >= 3) {
        $("#add-btn").hide();
    } else {
        $("#add-btn").show();
    }

    updateVariantPreview();
}