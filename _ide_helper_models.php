<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\AdminMessage
 *
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminMessage query()
 */
	class AdminMessage extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Announcement
 *
 * @property int $id
 * @property int|null $type_id
 * @property string|null $custom_type
 * @property string $title
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AnnouncementPicture[] $announcement_pictures
 * @property-read int|null $announcement_pictures_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 * @property-read int|null $likes_count
 * @property-read \App\Models\Type|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereCustomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Announcement whereUpdatedAt($value)
 */
	class Announcement extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AnnouncementPicture
 *
 * @property int $id
 * @property int|null $announcement_id
 * @property string $picture_name
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Announcement|null $announcement
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementPicture newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementPicture newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementPicture query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementPicture whereAnnouncementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementPicture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementPicture whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementPicture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementPicture wherePictureName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnnouncementPicture whereUpdatedAt($value)
 */
	class AnnouncementPicture extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Certificate
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property string $status
 * @property int $is_open_delivery
 * @property float $delivery_fee
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CertificateForm[] $certificateForms
 * @property-read int|null $certificate_forms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Requirement[] $requirements
 * @property-read int|null $requirements_count
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate query()
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereIsOpenDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Certificate whereUpdatedAt($value)
 */
	class Certificate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CertificateForm
 *
 * @property int $id
 * @property int $user_id
 * @property int $certificate_id
 * @property string $name
 * @property string $address
 * @property string|null $business_name
 * @property string|null $birthday
 * @property string|null $birthplace
 * @property string|null $contact_no
 * @property string|null $contact_person
 * @property string|null $contact_person_no
 * @property string|null $contact_person_relation
 * @property string|null $citizenship
 * @property string|null $purpose
 * @property string|null $date_requested
 * @property string|null $date_released
 * @property string|null $date_expiry
 * @property string|null $precint_no
 * @property string $civil_status
 * @property string|null $received_by
 * @property string $signature_picture
 * @property string $file_path
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Certificate $certificate
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Requirement[] $requirements
 * @property-read int|null $requirements_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm query()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereBirthplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereCitizenship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereCivilStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereContactNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereContactPersonNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereContactPersonRelation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereDateExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereDateReleased($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereDateRequested($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm wherePrecintNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereReceivedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereSignaturePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateForm whereUserId($value)
 */
	class CertificateForm extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CertificateFormOrder
 *
 * @property int $order_id
 * @property int $certificate_form_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormOrder whereCertificateFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormOrder whereUpdatedAt($value)
 */
	class CertificateFormOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CertificateFormRequirement
 *
 * @property int $certificate_form_id
 * @property int $requirement_id
 * @property string $file_name
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormRequirement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormRequirement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormRequirement query()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormRequirement whereCertificateFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormRequirement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormRequirement whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormRequirement whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormRequirement whereRequirementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateFormRequirement whereUpdatedAt($value)
 */
	class CertificateFormRequirement extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CertificateOrder
 *
 * @property int $order_id
 * @property int $certificate_form_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateOrder query()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateOrder whereCertificateFormId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateOrder whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateOrder whereUpdatedAt($value)
 */
	class CertificateOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CertificateRequirement
 *
 * @property int $certificate_id
 * @property int $requirement_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateRequirement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateRequirement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateRequirement query()
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateRequirement whereCertificateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateRequirement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateRequirement whereRequirementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CertificateRequirement whereUpdatedAt($value)
 */
	class CertificateRequirement extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $user_id
 * @property string $body
 * @property string $commentable_type
 * @property int $commentable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $commentable
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCommentableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 */
	class Comment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Complainant
 *
 * @property int $id
 * @property int|null $complaint_id
 * @property string $name
 * @property string $signature_picture
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Complaint|null $complaint
 * @method static \Illuminate\Database\Eloquent\Builder|Complainant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complainant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complainant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Complainant whereComplaintId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complainant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complainant whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complainant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complainant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complainant whereSignaturePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complainant whereUpdatedAt($value)
 */
	class Complainant extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Complaint
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $type_id
 * @property string|null $custom_type
 * @property string $reason
 * @property string $action
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Complainant[] $complainants
 * @property-read int|null $complainants_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Defendant[] $defendants
 * @property-read int|null $defendants_count
 * @property-read \App\Models\Type|null $type
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint query()
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereCustomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Complaint whereUserId($value)
 */
	class Complaint extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Defendant
 *
 * @property int $id
 * @property int|null $complaint_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Complaint|null $complaint
 * @method static \Illuminate\Database\Eloquent\Builder|Defendant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Defendant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Defendant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Defendant whereComplaintId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defendant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defendant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defendant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Defendant whereUpdatedAt($value)
 */
	class Defendant extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Document
 *
 * @property int $id
 * @property int|null $type_id
 * @property string|null $custom_type
 * @property string|null $description
 * @property string $year
 * @property string $pdf_name
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Type|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Document newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Document query()
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereCustomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document wherePdfName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Document whereYear($value)
 */
	class Document extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Employee
 *
 * @property int $id
 * @property string $name
 * @property int|null $term_id
 * @property string|null $custom_term
 * @property int|null $position_id
 * @property string|null $custom_position
 * @property string $description
 * @property string $picture_name
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Position|null $position
 * @property-read \App\Models\Term|null $term
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCustomPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereCustomTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee wherePictureName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee wherePositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereTermId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Employee whereUpdatedAt($value)
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Feedback
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $type_id
 * @property string|null $custom_type
 * @property string $polarity
 * @property string $message
 * @property string|null $admin_respond
 * @property string $status
 * @property int $is_anonymous
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property-read \App\Models\Type|null $type
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereAdminRespond($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereCustomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback wherePolarity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUserId($value)
 */
	class Feedback extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Like
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $likeable_type
 * @property int $likeable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $likeable
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Like newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Like query()
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereLikeableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereLikeableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Like whereUserId($value)
 */
	class Like extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LostAndFound
 *
 * @property int $id
 * @property int $user_id
 * @property string $item
 * @property string $last_seen
 * @property string $description
 * @property string $contact_information
 * @property string $picture_name
 * @property string $file_path
 * @property string $status
 * @property string $report_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound query()
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereContactInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereItem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereLastSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound wherePictureName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereReportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LostAndFound whereUserId($value)
 */
	class LostAndFound extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MissingPerson
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property float $height
 * @property string $height_unit
 * @property float $weight
 * @property string $weight_unit
 * @property int|null $age
 * @property string|null $eyes
 * @property string|null $hair
 * @property string $unique_sign
 * @property string $important_information
 * @property string $last_seen
 * @property string $contact_information
 * @property string $picture_name
 * @property string $file_path
 * @property string $status
 * @property string $report_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson query()
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereContactInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereEyes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereHair($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereHeightUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereImportantInformation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereLastSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson wherePictureName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereReportType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereUniqueSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MissingPerson whereWeightUnit($value)
 */
	class MissingPerson extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $ordered_by
 * @property int|null $delivered_by
 * @property float $total_price
 * @property string $pick_up_type
 * @property float $delivery_fee
 * @property string $pickup_date
 * @property string $application_status
 * @property string|null $order_status
 * @property string $location_address
 * @property float|null $user_long
 * @property float|null $user_lat
 * @property float|null $rider_long
 * @property float|null $rider_lat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CertificateForm[] $certificateForms
 * @property-read int|null $certificate_forms_count
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereApplicationStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveredBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLocationAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePickUpType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePickupDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRiderLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRiderLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserLong($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Ordinance
 *
 * @property int $id
 * @property int|null $type_id
 * @property string|null $custom_type
 * @property string $ordinance_no
 * @property string $title
 * @property string $date_approved
 * @property string $pdf_name
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Type|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance whereCustomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance whereDateApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance whereOrdinanceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance wherePdfName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ordinance whereUpdatedAt($value)
 */
	class Ordinance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Position
 *
 * @property int $id
 * @property int $ranking
 * @property string $name
 * @property string $job_description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Employee[] $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder|Position newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Position query()
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereJobDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereRanking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Position whereUpdatedAt($value)
 */
	class Position extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Project
 *
 * @property int $id
 * @property int|null $type_id
 * @property string|null $custom_type
 * @property string $name
 * @property string $description
 * @property float $cost
 * @property string $project_start
 * @property string $project_end
 * @property string $location
 * @property string $pdf_name
 * @property string $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Type|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCustomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePdfName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereProjectStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 */
	class Project extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Purok
 *
 * @property int $id
 * @property string $purok
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Purok newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purok newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Purok query()
 * @method static \Illuminate\Database\Eloquent\Builder|Purok whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purok whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purok wherePurok($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Purok whereUpdatedAt($value)
 */
	class Purok extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Report
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $type_id
 * @property string|null $custom_type
 * @property string $location_address
 * @property string $landmark
 * @property string $description
 * @property int $is_anonymous
 * @property string $urgency_classification
 * @property string $status
 * @property string|null $picture_name
 * @property string|null $file_path
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Type|null $type
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Report newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Report query()
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereCustomType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereIsAnonymous($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereLandmark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereLocationAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report wherePictureName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUrgencyClassification($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Report whereUserId($value)
 */
	class Report extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ReportType
 *
 * @property int $id
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Report[] $reports
 * @property-read int|null $reports_count
 * @method static \Illuminate\Database\Eloquent\Builder|ReportType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReportType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReportType query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReportType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReportType whereUpdatedAt($value)
 */
	class ReportType extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Requirement
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CertificateForm[] $certificateForms
 * @property-read int|null $certificate_forms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Certificate[] $certificates
 * @property-read int|null $certificates_count
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement query()
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Requirement whereUpdatedAt($value)
 */
	class Requirement extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Term
 *
 * @property int $id
 * @property string $name
 * @property string $year_start
 * @property string $year_end
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Employee[] $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder|Term newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Term newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Term query()
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereYearEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Term whereYearStart($value)
 */
	class Term extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Type
 *
 * @property int $id
 * @property string $name
 * @property string $model_type
 * @property \datetime|null $created_at
 * @property \datetime|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Announcement[] $announcements
 * @property-read int|null $announcements_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Complaint[] $complaints
 * @property-read int|null $complaints_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Document[] $documents
 * @property-read int|null $documents_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Ordinance[] $ordinances
 * @property-read int|null $ordinances_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Project[] $projects
 * @property-read int|null $projects_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Report[] $reports
 * @property-read int|null $reports_count
 * @method static \Illuminate\Database\Eloquent\Builder|Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type query()
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereUpdatedAt($value)
 */
	class Type extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string|null $barangay_id
 * @property int|null $purok_id
 * @property string $address
 * @property string|null $picture_name
 * @property string|null $file_path
 * @property int|null $is_verified
 * @property string $status
 * @property string|null $admin_status_message
 * @property int $user_role_id
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CertificateForm[] $certificateForms
 * @property-read int|null $certificate_forms_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Complaint[] $complaints
 * @property-read int|null $complaints_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Feedback[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read mixed $full_name
 * @property-read \App\Models\UserVerification|null $latest_user_verification
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $likes
 * @property-read int|null $likes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LostAndFound[] $lost_and_founds
 * @property-read int|null $lost_and_founds_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\MissingPerson[] $missing_persons
 * @property-read int|null $missing_persons_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Purok|null $purok
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Report[] $reports
 * @property-read int|null $reports_count
 * @property-read \App\Models\UserRole $user_role
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAdminStatusMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBarangayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePictureName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePurokId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserRoleId($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserRole
 *
 * @property int $id
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserRole whereUpdatedAt($value)
 */
	class UserRole extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserVerification
 *
 * @property int $id
 * @property int $user_id
 * @property string $credential_name
 * @property string $credential_file_path
 * @property string $status
 * @property string|null $admin_message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereAdminMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereCredentialFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereCredentialName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserVerification whereUserId($value)
 */
	class UserVerification extends \Eloquent {}
}

