const { __ } = wp.i18n;
const { SelectControl } = wp.components;
const { withSelect } = wp.data;

function TermList( {terms, value, onChange, checked} ){
    var _terms = [];
    if(terms != undefined){
        var arr = Object.values(terms);
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
            value={ value }
        />
    ];
}
export default withSelect( (select, props) => {
    const { taxonomie } = props;
    return {
        terms: select('core').getEntityRecords('taxonomy', taxonomie)
    };
})( TermList );