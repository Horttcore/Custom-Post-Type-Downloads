const { __ } = wp.i18n;
const { SelectControl, withAPIData, CheckboxControl, Panel, PanelHeader, PanelBody, Dashicon } = wp.components;

function TermList( {terms, taxonomie, value, onChange, checked} ){
    var _terms = [];
    if(terms.data != undefined){
        var arr = Object.values(terms.data);
        arr.forEach(element => {
            if(element.name != undefined && element.id != undefined){
                _terms.push({label: element.name, value: element.id})
            }
        });
    }
    const hasTerms = Array.isArray( _terms ) && _terms.length;
    if(!hasTerms){
        return [
            <div></div>
        ];
    }
    if(!Array.isArray(checked)){
        checked = [];
    }
    _terms.unshift({label: 'All', value: '-1'});
    return [
        <SelectControl
            { ...{onChange} }
            multiple
            label={ __('CPT Tax Terms') }
            options={ _terms.map( (term, i) => ({
                value: term.value,
                label: term.label
            }))}
        />
    ];
}

export default withAPIData( (props) => {
    const { taxonomie } = props;
    return {
        terms: '/wp/v2/'+taxonomie,
    };
})( TermList );