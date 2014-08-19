{**  
 * Fat-Free Framework
 * View and Template
 * 做法請看 http://fatfreeframework.com/views-and-templates#conditional-segments
 *}
<check if="{{ @callback=='' }}">
    <true>
        {{ @json | json_encode }}
    </true>
    <false>
        {{ @callback }}({{ @json | json_encode }});
    </false>
</check>
    