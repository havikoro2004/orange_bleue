<?php


namespace App\Services;


use App\Entity\Permission;

class getPermissionsMethodes
{
    public function getMethodes(Permission $permission ,$value):Permission
    {
        if ($value === 'isReadResa'){
            return $permission->setReadResa(!$permission->isReadResa());
        }
        if ($value === 'isEditResa'){
            return $permission->setEditResa(!$permission->isEditResa());
        }
        if ($value === 'isRemoveResa'){
            return $permission->setRemoveResa(!$permission->isRemoveResa());
        }
        if ($value === 'isReadPayment'){
            return $permission->setReadPayment(!$permission->isReadPayment());
        }
        if ($value === 'isEditPayment'){
            return $permission->setEditPayment(!$permission->isEditPayment());
        }
        if ($value === 'isManageDrink'){
            return $permission->setManageDrink(!$permission->isManageDrink());
        }
        if ($value === 'isAddSub'){
            return $permission->setAddSub(!$permission->isAddSub());
        }
        if ($value === 'isEditSub'){
            return $permission->setEditSub(!$permission->isEditSub());
        }
        if ($value === 'isRemoveSub'){
            return $permission->setRemoveSub(!$permission->isRemoveSub());
        }
        if ($value === 'isManageSchedules'){
            return $permission->setManageSchedules(!$permission->isManageSchedules());
        }

    }

}