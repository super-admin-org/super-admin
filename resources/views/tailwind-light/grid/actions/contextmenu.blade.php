@extends('admin::grid.actions.dropdown')

@section('child')
<script>

    var contextMenu = document.createElement("div");
    contextMenu.classList.add("context-menu");
    document.body.appendChild(contextMenu);
    var lastParentMenu;

    document.querySelectorAll("table.select-table>tbody>tr").forEach(tr=>{

        tr.addEventListener("contextmenu",function(e){

            hideContextMenu();

            if (event.target.tagName == "TD"){
                let tr = event.target.closest("tr");
                let key = tr.dataset.key;
                let row_menu = tr.querySelector('td.column-__actions__ .grid-actions-menu');
                lastParentMenu = row_menu.parentNode;
                contextMenu.innerHTML = '';
                contextMenu.appendChild(row_menu);
                show(contextMenu);

                var height = row_menu.offsetHeight;
                if (height > (document.body.clientHeight - e.pageY)) {
                    contextMenu.style.left = (e.pageX + 10)+"px";
                    contextMenu.style.top = (e.pageY - height)+"px";
                } else {
                    contextMenu.style.left = (e.pageX + 10)+"px";
                    contextMenu.style.top = (e.pageY - 10)+"px";
                }
            }

            e.preventDefault();
            e.stopPropagation();
            return false;
        },true)

    });

    document.addEventListener("contextmenu",function(e){
       hideContextMenu();
    },false);

    document.addEventListener("click",function(e){
       hideContextMenu();
    },false);

    function hideContextMenu(){
        let menu = contextMenu.querySelector(".grid-actions-menu");
        if (menu){
            lastParentMenu.appendChild(menu);
            hide(contextMenu);
        }
    }

</script>
<style>
    .select-table .column-__actions__ {
        display: none !important;
    }
    .context-menu {
        position: fixed;
        z-index: 9999;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(209,213,219,0.5);
        border-radius: 0.5rem;
        box-shadow: 0 8px 32px rgba(31,38,135,0.12);
        min-width: 140px;
        overflow: hidden;
    }
</style>
@endsection
