<template>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th v-for="column in columns" :key="column.name" @click="$emit('sort', column.name)"
                    :style="(column.name == null) ? '' :'cursor:pointer;'"
                    
                    >
                    {{ column.label }} <i class="fa" :class=" (column.name == null) ? '' : sortKey===column.name ? (sortOrders[column.name] > 0 ? 'fa-sort-up':'fa-sort-down')  : 'fa-sort text-grey'"></i>

                    </th>
                </tr>
            </thead>
            <slot></slot>
        </table>
    </div>
</template>
<script>
    export default{
        props:['columns', 'sortKey', 'sortOrders']
    }
</script>