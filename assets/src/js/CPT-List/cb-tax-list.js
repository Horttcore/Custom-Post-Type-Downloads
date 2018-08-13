const { __ } = wp.i18n;
const { SelectControl, withAPIData } = wp.components;

function TaxList( {taxonomies, posttype, value, onChange} ){
    var _taxonomies = [];
    if(taxonomies.data != undefined){
         var arr = Object.values(taxonomies.data);
        arr.forEach(element => {
            if(element.types.includes(posttype)){
                _taxonomies.push({label: element.name, value: [element.rest_base, element.slug]})
            }
        });
    }
    //console.log(taxonomies, _taxonomies, posttype);
    const hasTaxs = Array.isArray(_taxonomies) && _taxonomies.length;
    if(!hasTaxs){
        return [
            <div></div>
        ];
    }

    _taxonomies.unshift({label: 'All', value: ''});
    return (
        <SelectControl
        { ...{ onChange } }
            label={ __('Custom Posttypes Taxonomies') }
            options={_taxonomies}
            value={ value }
        />
    );
}

export default withAPIData( (props) => {
    return {
        taxonomies: '/wp/v2/taxonomies',
    };
})( TaxList );