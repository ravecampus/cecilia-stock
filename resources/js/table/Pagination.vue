<template>
    <div class="row">
        <div class="col-md-12">
            <div class="pagi-layout">
                <div class="pagi-flex">
                    <div class="pagi-range">
                        <strong>
                        {{ pagination.from }} - {{ pagination.to }}
                        of {{ pagination.total }}
                        </strong>
                    </div>
                    <nav class="pagination">
                        <ul class="pagination">
                            <li v-bind:class="{disabled:!pagination.prevPageUrl}" class="page-item">
                                <a  @click="$emit('prev')"  class="page-link page-link-sm">
                                    <span class="fa fa-angle-left"></span>
                                </a>
                            </li>
                            <li v-bind:class="{disabled:!pagination.nextPageUrl}" class="page-item">
                                <a  @click="$emit('next')"  class="page-link btn-link-sm">
                                     <span class="fa fa-angle-right"></span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
       
    </div>
</template>

<script>
    export default{
        props:['pagination']
    }
</script>