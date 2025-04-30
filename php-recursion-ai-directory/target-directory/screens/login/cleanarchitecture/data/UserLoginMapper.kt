package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data

//Mapper (DTO → Model)

fun UserLoginDto.toModel(): UserLoginModel {
    return UserLoginModel(
        type = this.type,
        message = this.message,
        data = this.data?.toModel()
    )
}

fun UserLoginDataDto.toModel(): UserLoginDataModel {
    return UserLoginDataModel(
        isLoggedIn = this.isLoggedIn,
        data = this.data?.toModel()
    )
}

fun UserLoginDataItemDto.toModel(): UserLoginDataItemModel {
    return UserLoginDataItemModel(
        accessToken = this.accessToken,
        refreshToken = this.refreshToken
    )
}

//Mapper (Model → DTO)

fun UserLoginModel.toDto(): UserLoginDto {
    return UserLoginDto(
        type = this.type,
        message = this.message,
        data = this.data?.toDto()
    )
}

fun UserLoginDataModel.toDto(): UserLoginDataDto {
    return UserLoginDataDto(
        isLoggedIn = this.isLoggedIn,
        data = this.data?.toDto()
    )
}

fun UserLoginDataItemModel.toDto(): UserLoginDataItemDto {
    return UserLoginDataItemDto(
        accessToken = this.accessToken,
        refreshToken = this.refreshToken
    )
}

/*
fun UserLoginDto.toUserLoginModel(): UserLoginModel {
    return UserLoginModel(userAuthToken, isLoggedIn)
}*/
