<script type="text/javascript" src="/public/js/home/index.js?v=<?php echo date("Y-m-d h:i:sa");?>"></script>

<div class="container pt-2" ng-app="app_controller">

    
    
    <div class="container">{{courses}} {{is_load}}

        <h5 class="mb-2 mt-3 align-middle" id="titleDisplayButton">
            ประเภทอาหาร : 
            <!-- <a class="btn btn-light mr-2 my-1" id="button{{menuCategoryItem.menu_category_id}}" ng-repeat="menuCategoryItem in menuCategoryList" ng-click="filterMenuCategory(menuCategoryItem.menu_category_id)">
                {{menuCategoryItem.menu_category_name}}
            </a> -->
        </h5>

        <!-- <div class="row row-cols-2 row-cols-md-4 p-0" style="padding: 0;" id="menuRow">

            <div class="col p-2" ng-repeat="course in courses | filter:{menu_category_name: menuCategory, menu_name: menu_name_filter}">

                <div class="card courseCard" style="width:100%; border-radius:10px;">
                    <div class="aspect-ratio-box">
                        <img src="/img/menu/{{course.menu_id}}.jpg" style="object-fit:cover; border-radius:10px 10px 0px 0px;">
                    </div>
                    <div class="card-body">
                        
                        <h5 class="card-title">{{course.menu_name}}</h5>

                        <div class="row">
                            <div class="col">
                                <h6>{{course.menu_price-course.menu_discount}}.- <del style="font-size:smaller; color:#aaa;" ng-show="course.menu_discount!=0">{{course.menu_price}}.-</del></h6>
                            </div>
                            <div class="col">
                                <button class="btn btn-light btn-sm align-middle px-2 float-right" ng-click="addMenu(course)" ng-show="member!='null'">
                                    <h6 class="my-1">
                                        <i class="fa fa-lg fa-plus my-1" aria-hidden="true"></i>
                                        <i class="fa fa-lg fa-shopping-basket my-1" aria-hidden="true"></i>
                                    </h6>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div> -->

    </div>

</div>
        
<style>
    .itemCol { color: #666; background-color: #f8f9fa; border: 1px solid #ddd; text-decoration: none; }
    .itemCol:hover { color: #222; transform: translate(0,-4px); border: 1px solid #999; text-decoration: underline; }
    .aspect-ratio-box {
        position: relative;
    }

    /* Create a pseudo element that uses padding-bottom to take up space */
    .aspect-ratio-box::after {
        display: block;
        content: '';
        /* 16:9 aspect ratio */
        padding-bottom: 56.25%;
    }

    /* Image is positioned absolutely relative to the parent element */
    .aspect-ratio-box img {
        /* Image should match parent box size */
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
    }

    .courseCard:hover {
        transform: translate(0,-4px);
        box-shadow: 0 4px 8px lightgrey;
    }
</style>