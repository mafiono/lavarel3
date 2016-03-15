@extends('layouts.portal')

\\                                                                                                                                              @include('portal.profile.head', ['active' => 'COMUNICAÇÃO'])

        @include('portal.communications.head_communication', ['active' => 'MENSAGENS'])



        @include('portal.profile.bottom')
                        
@stop

@section('scripts')

        <!--Start of Zopim Live Chat Script-->
        {{--<script type="text/javascript">--}}
                {{--window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=--}}
                        {{--d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.--}}
                {{--_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");--}}
                        {{--$.src="//v2.zopim.com/?3kNWQ5KzE7a2NPtIfOcWCfBKcArIXVKA";z.t=+new Date;$.--}}
                                {{--type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");--}}
        {{--</script>--}}
        <!--End of Zopim Live Chat Script-->

        <!-- Start of tntexplosives Zendesk Widget script -->
        {{--<script>/*<![CDATA[*/window.zEmbed||function(e,t){var n,o,d,i,s,a=[],r=document.createElement("iframe");window.zEmbed=function(){a.push(arguments)},window.zE=window.zE||window.zEmbed,r.src="javascript:false",r.title="",r.role="presentation",(r.frameElement||r).style.cssText="display: none",d=document.getElementsByTagName("script"),d=d[d.length-1],d.parentNode.insertBefore(r,d),i=r.contentWindow,s=i.document;try{o=s}catch(c){n=document.domain,r.src='javascript:var d=document.open();d.domain="'+n+'";void(0);',o=s}o.open()._l=function(){var o=this.createElement("script");n&&(this.domain=n),o.id="js-iframe-async",o.src=e,this.t=+new Date,this.zendeskHost=t,this.zEQueue=a,this.body.appendChild(o)},o.write('<body onload="document._l();">'),o.close()}("//assets.zendesk.com/embeddable_framework/main.js","tntexplosives.zendesk.com");--}}
                {{--/*]]>*/</script>--}}
        <!-- End of tntexplosives Zendesk Widget script -->

@stop