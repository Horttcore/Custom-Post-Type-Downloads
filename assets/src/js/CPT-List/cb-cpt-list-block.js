//import PtList from './cb-pt-list';
import TaxList from './cb-tax-list';
import TermList from './cb-term-list';

const { Component } = wp.element;
const { __ } = wp.i18n;
const { InspectorControls } = wp.editor;
const { TextControl, SelectControl, RangeControl, withAPIData } = wp.components;

class DownloadsListBlock extends Component {
    constructor(){
        super( ...arguments );
    }
    render() {
        const posts = this.props.posts.data;
        const {attributes, setAttributes, className, isSelected} = this.props;
        const { posttype, taxonomie, term, amount, orderBy, order } = attributes;
        const classes = ((className) ? className : '' ) + ' list-wrapper ';

        var _posts = [];
        if(posts != undefined && taxonomie != undefined && taxonomie.length != 0 && term != undefined && term.length != 0){
            for(var i = 0; i < posts.length; i++){
                // check if we have any value set in given taxonomie cause this will be an arry of ids
                if(posts[i][taxonomie[0]] != undefined && posts[i][taxonomie[0]].length > 0){
                    _posts.push(posts[i]);
                }
            }
        } else {
            _posts = posts;
        }
        
        const termList = <TermList
            taxonomie={ taxonomie[0] }
            value={ term }
            onChange={ (newTerm) => {
                var _term = [];
                if(Array.isArray(newTerm)){
                    newTerm.map( (item, i) => {
                        if( _term.indexOf(item) == -1){
                            _term.push(item);
                        }
                    })
                } else {
                    _term.push(newTerm);
                }
                // @todo trigger change cause checked will stuck
                setAttributes( {term: _term} );
            }}
        />
        const inspectorControls = isSelected && (
            <InspectorControls key="inspector">
                <TaxList
                    posttype={ 'download' }
                    value={ taxonomie }
                    onChange={ (newTaxonomie) => {
                        setAttributes( {taxonomie: newTaxonomie.split(',')} );
                        // reset other related values
                        setAttributes( {term: ''} );
                    }}
                />
                { termList }
                <RangeControl
                    label={ __('Amount')}
                    value={ amount }
                    onChange={ (newAmount) => {
                        setAttributes( {amount: newAmount})
                    }}
                    min={ 0 }
                    max={ 100 }
                />
                <TextControl
                    label={ __('Order By')}
                    value={ orderBy }
                    onChange={ (newOrderBy) => {
                        setAttributes( {orderBy: newOrderBy} )
                    }}
                />
                <SelectControl
                    label={ __('Order') }
                    value={ order }
                    options={[
                        {label: __('Ascending'), value: 'asc'},
                        {label: __('Decending'), value: 'desc'},
                    ]}
                    onChange={ (newOrder) => {
                        setAttributes( {order: newOrder} )
                    }}
                />
            </InspectorControls>
        );

        var hasPosts = Array.isArray( _posts ) && _posts.length;
        if(!hasPosts){
            return [
                inspectorControls,
                <div className={ classes } key="container">
                    { __('No Downloads found') }
                </div>
            ];
        }
        // @todo change edit output
        return [
            inspectorControls,
            <div className={ classes } key="container">
                    { _posts.map( (post, i) => 
                        <div class="downloads-wrapper" key={ i }>
                            <img class="downloads-image" src={ post.thumbnail} />
                            { post.title.rendered }
                        </div>
                    )}
            </div>
        ];
    
    }
}
export default withAPIData( (props) => {
    const { posttype, taxonomie, term, amount, orderBy, order } = props.attributes;
    const _order = String(order).toLowerCase();
    var attrs = {
        order: _order,
		orderby: orderBy,
		_fields: [ 'date_gmt', 'link', 'title', 'content', 'meta', 'thumbnail'],
    }
    if(amount > 0){
        attrs.per_page = amount;
    }
    // retrieve taxonomies in fields
    if(taxonomie[0] != undefined){
        attrs._fields.push(taxonomie[0]);
    }
    // retrieve posts with a given term
    if(taxonomie[0] != "" && typeof term != 'undefined' && term.length != 0){
        if(Array.isArray(term)){
            attrs[taxonomie[0]] = term;
        } else {
            attrs[taxonomie[0]] = term.map( (item, i) => {
                if(typeof item != 'undefined'){
                    return item.split(',')[1];
                }
            });
        }
    }
    const queryString = serialize( attrs, value => ! isUndefined( value ) );
    
    return {
        posts: `/wp/v2/${posttype[0]}${queryString}`,
    };
})( DownloadsListBlock );

function serialize( obj ) {
    return '?'+Object.keys(obj).reduce(function(a,k){a.push(k+'='+encodeURIComponent(obj[k]));return a},[]).join('&')
  }