<!DOCTYPE html>
<html>

<body>

    <div class="container mt-3" ng-controller="moduleAppController">

        <h2 class="mt-3">เพิ่ม bank statement</h2>

        <div class="card shadow p-1 mt-3" style="border:none; border-radius:10px;">
            <div class="card-body">
                <input type="file" class="form-control-file" id="excel" name="excel" file-reader="fileContent"
                    ng-model="excel">

            </div>

            <button type="button" class="btn btn-default btn-block my-1" ng-click="update()">check</button>


            <table class="table table-hover my-1" ng-show="lines.length > 0">
                <tr>
                    <th>date</th>
                    <th>time</th>
                    <th>ammount</th>

                </tr>
                <tr ng-repeat="line in lines track by $index">
                    <td style="text-align: center;">{{line[0]}}</td>
                    <td style="text-align: center;">{{line[1]}}</td>
                    <td style="text-align: center;">{{line[2]}}</td>
                </tr>
            </table>

            <button type="button" class="btn btn-default btn-block my-1" ng-click="upload()"
                ng-show="checked">upload</button>
        </div>
        <!-- review uploaded -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- FORM VALIDATION -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

        <script>
            addModal('formValidate1', 'เพิ่มคะแนน Learning Point', 'ยังไม่ได้กรอกเลข SP เลยน้าา');
        </script>

        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->
        <!-- -------------------------------------------------------------------------------------------------------------------------------------------------------------- -->

    </div>



</body>

</html>

<style>
    td {
        border-bottom: 1px solid lightgray;
    }

    th {
        border-bottom: 1px solid lightgray;
        text-align: center;
    }
</style>

<script>
    app.directive('fileReader', function () {
        return {
            scope: {
                fileReader: "="
            },
            link: function (scope, element) {
                $(element).on('change', function (changeEvent) {
                    var files = changeEvent.target.files;
                    if (files.length) {
                        var r = new FileReader();
                        r.onload = function (e) {
                            var contents = e.target.result;
                            scope.$apply(function () {
                                scope.fileReader = contents;
                            });
                        };

                        r.readAsText(files[0]);
                    }
                });
            }
        };
    });


    app.controller('moduleAppController', function ($scope, $http, $compile) {
        $scope.lines = [];
        $scope.fileContent = "";
        $scope.header = [];
        $scope.checked = false;


        $scope.update = () => {
            $scope.checked = true;
            allLine = $scope.fileContent.split("\r\n");
            $scope.lines = [];
            if (allLine.length > 0) {
                $scope.header = allLine[0];
            }
            for (let i = 1; i < allLine.length - 1; i++) {
                tempLine = allLine[i].split(',');
                if (tempLine.length != 3) {
                    //error don't let upload
                    $scope.checked = false;
                }
                if (tempLine[4] == '') tempLine[4] = "CURRENT DATE";
                $scope.lines.push(tempLine);
            }

        }


        $scope.upload = () => {
            $.post("/fin/bank_statement/post_bank_statement", {
                post: true,
                statement: $scope.lines
            }, function (data) {
                console.log(data)
                location.reload();
            }).fail(function (error) {
                console.log(error)
            })
        }











    })
</script>