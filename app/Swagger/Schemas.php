<?php

namespace App\Swagger;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Budi Santoso"),
 *     @OA\Property(property="member_number", type="string", example="MEM001"),
 *     @OA\Property(property="identity_number", type="string", example="3201010101900001"),
 *     @OA\Property(property="birth_date", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="phone_number", type="string", example="081234567890"),
 *     @OA\Property(property="address", type="string", example="Jl. Raya No. 123"),
 *     @OA\Property(property="occupation", type="string", example="Petani"),
 *     @OA\Property(property="identity_card_photo", type="string", nullable=true, example="employees/identity_cards/photo.jpg"),
 *     @OA\Property(property="self_photo", type="string", nullable=true, example="employees/photos/photo.jpg"),
 *     @OA\Property(property="role", type="string", enum={"group_member","employee","admin"}, example="group_member"),
 *     @OA\Property(property="is_verified", type="boolean", example=false),
 *     @OA\Property(property="is_active", type="boolean", example=false),
 *     @OA\Property(property="work_area_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="group_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="WorkArea",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name_work_area", type="string", example="Wilayah Kerja A"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="MemberGroup",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="number", type="string", example="GRP001"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Kelompok tani sejahtera"),
 *     @OA\Property(property="shared_liability_fund_amount", type="integer", example=50000),
 *     @OA\Property(property="group_fund_amount", type="integer", example=30000),
 *     @OA\Property(property="social_fund_amount", type="integer", example=20000),
 *     @OA\Property(property="total_shared_liability_fund", type="integer", example=500000),
 *     @OA\Property(property="total_group_fund", type="integer", example=300000),
 *     @OA\Property(property="total_social_fund", type="integer", example=200000),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="work_area_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="chairman_id", type="integer", nullable=true, example=1),
 *     @OA\Property(property="facilitator_id", type="integer", nullable=true, example=2),
 *     @OA\Property(property="treasurer_id", type="integer", nullable=true, example=3),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Schemas
{
}

