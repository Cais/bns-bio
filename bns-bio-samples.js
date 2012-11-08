/**
 * Created with JetBrains PhpStorm.
 * User: ecaissie
 * Date: 11/8/12
 * Time: 3:00 PM
 * To change this template use File | Settings | File Templates.
 */
jQuery( document ).ready( function( $ ) {
    /** Note: $() will work as an alias for jQuery() inside of this function */

    $( '.bns-bio').wrapInner( '<table />' );
    $( '.bns-bio table').wrapInner( '<tr />' );
    $( '.bns-bio table tr').wrapInner( '<td />' );
    // $( '.bns-bio table tr td span + span').after( '</td>' ); // close, but not quite
    // $( '.bns-bio span + span').wrap( '<td />' );

} );
