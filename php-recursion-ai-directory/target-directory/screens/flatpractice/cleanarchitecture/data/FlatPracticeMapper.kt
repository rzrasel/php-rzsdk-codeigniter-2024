package com.rzrasel.wordquiz.presentation.screens.flatpractice.cleanarchitecture.data

//Mapper (DTO → Model) (DTO To Model)

fun FlatPracticeDto.toModel(): FlatPracticeModel {
    return FlatPracticeModel(
        type = type,
        message = message,
        data = data?.toModel()
    )
}

fun FlatPracticeDataDto.toModel(): FlatPracticeDataModel {
    return FlatPracticeDataModel(
        nextQuestionIndex = nextQuestionIndex,
        isLastQuestion = isLastQuestion,
        questionSet = ArrayList(questionSet?.map { it.toModel() } ?: emptyList())
    )
}

fun FlatPracticeQuestionItemDto.toModel(): FlatPracticeQuestionItemModel {
    return FlatPracticeQuestionItemModel(
        questionId = questionId,
        question = question,
        answerSet = answerSet.map { it.toModel() }
    )
}

fun FlatPracticeAnswerItemDto.toModel(): FlatPracticeAnswerItemModel {
    return FlatPracticeAnswerItemModel(
        answerId = answerId,
        answer = answer,
        isTure = isTure
    )
}

//Mapper (Model → DTO) (Model To DTO)

fun FlatPracticeModel.toDto(): FlatPracticeDto {
    return FlatPracticeDto(
        type = type,
        message = message,
        data = data?.toDto()
    )
}

fun FlatPracticeDataModel.toDto(): FlatPracticeDataDto {
    return FlatPracticeDataDto(
        nextQuestionIndex = nextQuestionIndex,
        isLastQuestion = isLastQuestion,
        questionSet = ArrayList(questionSet?.map { it.toDto() } ?: emptyList())
    )
}

fun FlatPracticeQuestionItemModel.toDto(): FlatPracticeQuestionItemDto {
    return FlatPracticeQuestionItemDto(
        questionId = questionId,
        question = question,
        answerSet = answerSet.map { it.toDto() }
    )
}

fun FlatPracticeAnswerItemModel.toDto(): FlatPracticeAnswerItemDto {
    return FlatPracticeAnswerItemDto(
        answerId = answerId,
        answer = answer,
        isTure = isTure
    )
}