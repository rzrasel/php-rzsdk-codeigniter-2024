package com.rzrasel.wordquiz.presentation.screens.dashboard.cleanarchitecture.data

import kotlin.math.acosh

//Mapper (DTO → Model) (DTO To Model)

fun DashboardDto.toDashboardModel(): DashboardModel {
    return DashboardModel(
        type = type,
        message = message,
        data = data?.toDataModel()
    )
}

fun DashboardDataDto.toDataModel(): DashboardDataModel {
    return DashboardDataModel(
        accessToken = accessToken,
        data = data.map { it.toQuizListModel() } as ArrayList<DashboardQuizItemModel>
    )
}

fun DashboardQuizItemDto.toQuizListModel(): DashboardQuizItemModel {
    return DashboardQuizItemModel(
        quizId = quizId,
        quizTitle = quizTitle,
        quizDescription = quizDescription,
        totalQuestions = totalQuestions,
        createdAt = createdAt,
        updatedAt = updatedAt,
        categories = categories.map { it.toCategoryModel() } as ArrayList<DashboardCategoryModel>
    )
}

fun DashboardCategoryDto.toCategoryModel(): DashboardCategoryModel {
    return DashboardCategoryModel(
        categoryId = categoryId,
        categoryTitle = categoryTitle,
        categoryType = categoryType,
        accessMode = accessMode,
        taskItems = taskItems.map { it.toTaskItemModel() } as ArrayList<DashboardTaskItemModel>
    )
}

fun DashboardTaskItemDto.toTaskItemModel(): DashboardTaskItemModel {
    return DashboardTaskItemModel(
        itemId = itemId,
        itemTitle = itemTitle,
        itemSubtitle = itemSubtitle,
        itemButtonLabel = itemButtonLabel,
        accessMode = accessMode,
        itemType = itemType,
        /*quizAttempt = quizAttempt?.toQuizAttemptModel(),
        leaderboard = ArrayList(leaderboard)*/
    )
}

fun QuizAttemptDto.toQuizAttemptModel(): QuizAttemptModel {
    return QuizAttemptModel(
        totalQuestions = totalQuestions,
        correctAnswers = correctAnswers,
        wrongAnswers = wrongAnswers,
        score = score
    )
}

//Mapper (Model → DTO) (Model To DTO)

fun DashboardModel.toDashboardDto(): DashboardDto {
    return DashboardDto(
        type = type,
        message = message,
        data = data?.toDataDto()
    )
}

fun DashboardDataModel.toDataDto(): DashboardDataDto {
    return DashboardDataDto(
        accessToken = accessToken,
        data = data.map { it.toDataListDto() } as ArrayList<DashboardQuizItemDto>
    )
}

fun DashboardQuizItemModel.toDataListDto(): DashboardQuizItemDto {
    return DashboardQuizItemDto(
        quizId = quizId,
        quizTitle = quizTitle,
        quizDescription = quizDescription,
        totalQuestions = totalQuestions,
        createdAt = createdAt,
        updatedAt = updatedAt,
        categories = categories.map { it.toCategoryDto() } as ArrayList<DashboardCategoryDto>
    )
}

fun DashboardCategoryModel.toCategoryDto(): DashboardCategoryDto {
    return DashboardCategoryDto(
        categoryId = categoryId,
        categoryTitle = categoryTitle,
        categoryType = categoryType,
        accessMode = accessMode,
        taskItems = taskItems.map { it.toTaskItemDto() } as ArrayList<DashboardTaskItemDto>
    )
}

fun DashboardTaskItemModel.toTaskItemDto(): DashboardTaskItemDto {
    return DashboardTaskItemDto(
        itemId = itemId,
        itemTitle = itemTitle,
        itemSubtitle = itemSubtitle,
        itemButtonLabel = itemButtonLabel,
        accessMode = accessMode,
        itemType = itemType,
        /*quizAttempt = quizAttempt?.toQuizAttemptDto(),
        leaderboard = ArrayList(leaderboard)*/
    )
}

fun QuizAttemptModel.toQuizAttemptDto(): QuizAttemptDto {
    return QuizAttemptDto(
        totalQuestions = totalQuestions,
        correctAnswers = correctAnswers,
        wrongAnswers = wrongAnswers,
        score = score
    )
}