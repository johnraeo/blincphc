<template>
    <div class="page_loader" >
        <div class="page_loader_cont">
            <img :src="page_loader_svg" > 
        </div>
    </div>
</template>

<script>

export default{
	data() {
        return {
            page_loader_svg: '/svg/loader.svg'
        }
    },
}

</script>