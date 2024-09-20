<?php   


?>



<tr class="moderator-row">
    <td class="text-center">
        <img class="moderator-profile-pic" src="/esas/esas_moderator/images/' . $profilePic . '" 
            alt="' . $fullName . ' profile picture" 
            style="width: 50px; height: 50px; border-radius: 50%;">
    </td>
    <td class="moderator-name"><?php echo $fullName?></td>
    <td class="moderator-club"><?php echo $clubNames?></td>
    <td class="moderator-email"><?php echo $email?></td>
    <td class="moderator-phone"><?php echo $phoneNumber?></td>
    <td class="moderator-department"><?php echo $department?></td>
    <td class="text-center">
        <a href="../public/crud/moderators/moderator_read.php?moderator_id=' . htmlspecialchars($row['moderator_id']) . '" class="mr-2" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>
        <a href="../public/crud/moderators/moderator_update.php?moderator_id=' . htmlspecialchars($row['moderator_id']) . '" class="mr-2" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>
        <a href="../public/crud/moderators/moderator_delete.php?moderator_id=' . htmlspecialchars($row['moderator_id']) . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>
    </td>
</tr>