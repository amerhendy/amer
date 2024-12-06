(function(){    
    bpFieldInitSelect2FromAjax=(element)=>{
        var uniqueid=$(element).attr('uniqueid');
        registerSelect2WantedData(uniqueid);
        var select2AjaxMultipleFetchSelectedEntries = setPromiseSelect2(uniqueid);
        setSelect2Info(uniqueid);
        setSelect2BasicAjax(uniqueid);
        select2f=window.Amer.forms[uniqueid].select2;
        select2f['ajax']['data']=function (params) {return bpFieldInitSelect2FromAjax_ajax_data(params,uniqueid);};
        select2f['ajax']['processResults']=function (data, params) {return bpFieldInitSelect2FromAjax_ajax_processResults(data, params,uniqueid);};
        if (!$(element).hasClass("select2-hidden-accessible"))
            {
                var formatter = new RepositoryFormatter()
                $(element).select2(select2f);

            }
    }
    function RepositoryFormatter() {}
    
    bpFieldInitSelect2FromAjaxSetSelectedOptions=()=>{

    };
})(jQuery);
