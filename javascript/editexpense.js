//Script to set the select fields on edit account page
//to the previously established values
//
//function to select the correct option of the select list
function setSelectedIndex(s, valsearch)
{
    for (i = 0; i< s.options.length; i++)
    {
        if (s.options[i].value === valsearch)
        {
            s.options[i].selected = true;
            break;
        }
    }
}

//When document is ready, we set the correct select options
$(document).ready(function() {
    setSelectedIndex(document.getElementById("category"), category);
    setSelectedIndex(document.getElementById("account"), type);
});
