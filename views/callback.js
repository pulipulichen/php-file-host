{**  
 * Fat-Free Framework
 * View and Template
 * 做法請看 http://fatfreeframework.com/views-and-templates#conditional-segments
 *}
<check if="{{ @GET.callback=='' }}">
    <true>
        {{ @json | json_encode }}
    </true>
    <false>
        {{ @GET.callback }}({{ @json | json_encode }});
    </false>
</check>
    