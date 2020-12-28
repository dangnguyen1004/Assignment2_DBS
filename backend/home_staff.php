<?php

include "conn.php";

function findDepartment($id, $conn)
{
    $findQuery = "SELECT * FROM department WHERE id = '$id'";
    $resultFind = mysqli_query($conn, $findQuery);
    if (mysqli_num_rows($resultFind) > 0) {
        $name = mysqli_fetch_assoc($resultFind)['name'];
        if ($name == 'cashier') {
            return 'Thu ngân';
        }
        if ($name == 'manager') {
            return 'Quản lý';
        }
        if ($name == 'driver') {
            return 'Tài xế';
        }
        if ($name == 'warehouse mnanager') {
            return 'Quản lý kho';
        }
        if ($name == 'accountant') {
            return 'Kế toán';
        }
        if ($name == 'worker') {
            return 'Lao công';
        }
        if ($name == 'seller') {
            return 'Bán hàng';
        }
    } else {
        return '';
    }
}


function findIdDepartment($name){
    if ($name == 'cashier') {
        return 1;
    }
    if ($name == 'manager') {
        return 2;
    }
    if ($name == 'driver') {
        return 3;
    }
    if ($name == 'warehouse mnanager') {
        return 4;
    }
    if ($name == 'accountant') {
        return 5;
    }
    if ($name == 'worker') {
        return 6;
    }
    if ($name == 'seller') {
        return 7;
    }
}




if (isset($_POST['readStaffData'])) {
    $query = "SELECT * FROM staff";
    $result = mysqli_query($conn, $query);
    $department = $_POST['department'];

    if (mysqli_num_rows($result) > 0) {
        $data = '
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên</th>
                    <th>
                    <select onchange="filter(this.value);" style="font-size: 15px;">
                        <option value="" '.isSelected($department, 0).'>Toàn bộ</option>
                        <option value="cashier" '.isSelected($department, 1).'>Thu ngân</option>
                        <option value="manager" '.isSelected($department, 2).'>Quản lý</option>
                        <option value="driver" '.isSelected($department, 3).'>Tài xế</option>
                        <option value="warehouse mnanager" '.isSelected($department, 4).'>Thủ Kho</option>
                        <option value="worker" '.isSelected($department, 5).'>Lao công</option>
                        <option value="accountant" '.isSelected($department, 6).'>Kế toán</option>
                        <option value="seller" '.isSelected($department, 7).'>Bán hàng</option>
                    </select>
                    </th>
                    <th>Địa chỉ</th>
                    <th>Số điện thoại</th>
                    <th>Cập nhật</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
        ';
        if ($department != 0) {
            while ($record = mysqli_fetch_assoc($result)) {
                if ($record['department_id'] == $department) {
                    $data .= '
                    <tr id="record' . $record['id'] . '">   
                    <td>' . $record['id'] . '</td>
                    <td>' . $record['name'] . '</td>
                    <td>' . findDepartment($record['department_id'], $conn) . '</td>
                    <td>' . $record['address'] . '</td>
                    <td>' . $record['phone'] . '</td>
                    <td><button onclick="editStaff(' . $record['id'] . ');">Cập Nhật</button></td>
                    <td><button onclick="deleteStaff(' . $record['id'] . ');">Xóa</button></td>
                    </tr>
                    ';
                }
            }
        } else {
            while ($record = mysqli_fetch_assoc($result)) {
                $data .= '
                <tr id="record' . $record['id'] . '">   
                <td>' . $record['id'] . '</td>
                <td>' . $record['name'] . '</td>
                <td>' . findDepartment($record['department_id'], $conn) . '</td>
                <td>' . $record['address'] . '</td>
                <td>' . $record['phone'] . '</td>
                <td><button onclick="editStaff(' . $record['id'] . ');">Cập Nhật</button></td>
                <td><button onclick="deleteStaff(' . $record['id'] . ');">Xóa</button></td>
                </tr>
                ';
            }
        }
        $data .= '
                <tr>
                    <td>#</td>
                    <td><input type="text" id="staffName" placeholder="Tên"></td>
                    <td>
                        <select id="department">
                            <option value="cashier">Thu ngân</option>
                            <option value="manager">Quản lý</option>
                            <option value="driver">Tài xế</option>
                            <option value="warehouse mnanager">Thủ Kho</option>
                            <option value="worker">Lao công</option>
                            <option value="accountant">Kế toán</option>
                            <option value="seller">Bán hàng</option>
                        </select>
                    </td>
                    <td><input type="text" id="staffAddr" placeholder="Địa chỉ"></td>
                    <td><input type="text" id="staffPhone" placeholder="Số ĐT"></td>
                    <td><button onclick="addStaff();">Thêm</button></td>
                    <td><button>Hủy</button></td>
                </tr>
            </tbody>
        </table>
        ';
        echo $data;
    } else {
        echo 'fail';
    }
}


if (isset($_POST['addStaff'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];

    $findQuery = "CALL add_staff('$name','$address','$phone',NULL,'$department');";
    $resultFind = mysqli_query($conn, $findQuery);
    if ($resultFind) {
        echo 'success';
    } else {
        echo 'fail';
    }
}

if (isset($_POST['deleteStaff'])) {
    $id = $_POST['id'];
    $findQuery = "CALL delete_staff('$id');";
    $resultFind = mysqli_query($conn, $findQuery);
    if ($resultFind) {
        echo 'success';
    } else {
        echo 'fail';
    }
}

function isSelected($id, $dep)
{
    if ($id == $dep) {
        return 'selected';
    } else {
        return '';
    }
}

if (isset($_POST['editStaff'])) {
    $id = $_POST['id'];
    $findQuery = "SELECT * FROM staff WHERE id = '$id';";
    $resultFind = mysqli_query($conn, $findQuery);
    if (mysqli_num_rows($resultFind) > 0) {
        $staff = mysqli_fetch_assoc($resultFind);
        echo '
        <td>' . $staff['id'] . '</td>
        <td><input type="text" id="staffName' . $staff['id'] . '" placeholder="Tên" value="' . $staff['name'] . '"></td>
        <td>
            <select id="department' . $staff['id'] . '">
                <option value="cashier" ' . isSelected($staff['department_id'], 1) . '>Thu ngân</option>
                <option value="manager" ' . isSelected($staff['department_id'], 2) . '>Quản lý</option>
                <option value="driver" ' . isSelected($staff['department_id'], 3) . '>Tài xế</option>
                <option value="warehouse mnanager" ' . isSelected($staff['department_id'], 4) . '>Thủ Kho</option>
                <option value="worker" ' . isSelected($staff['department_id'], 5) . '>Lao công</option>
                <option value="accountant" ' . isSelected($staff['department_id'], 6) . '>Kế toán</option>
                <option value="seller">Bán hàng</option>
            </select>
        </td>
        <td><input type="text" id="staffAddr' . $staff['id'] . '" placeholder="Địa chỉ" value="' . $staff['address'] . '"></td>
        <td><input type="text" id="staffPhone' . $staff['id'] . '" placeholder="Số ĐT" value="' . $staff['phone'] . '"></td>
        <td><button onclick="updateStaff(' . $staff['id'] . ');">Cập nhật</button></td>
        <td><button onclick="readOrderData();">Hủy</button></td>
        ';
    } else {
        echo 'fail';
    }
}




if (isset($_POST['updateStaff'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];

    $findQuery = "CALL update_staff('$id','$name','$address','$phone',NULL,'$department');";
    $resultFind = mysqli_query($conn, $findQuery);
    if ($resultFind) {
        echo 'success';
    } else {
        echo 'fail';
    }
}
