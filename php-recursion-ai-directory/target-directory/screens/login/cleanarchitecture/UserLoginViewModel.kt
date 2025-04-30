package com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture

import android.util.Log
import androidx.compose.runtime.State
import androidx.compose.runtime.mutableStateOf
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.rzrasel.wordquiz.network.NetworkResult
import com.rzrasel.wordquiz.presentation.screens.data.UserModel
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginDataItemModel
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginDataModel
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.data.UserLoginModel
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.state.UserLoginErrorState
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.state.UserLoginState
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.state.UserLoginUiEvent
import com.rzrasel.wordquiz.presentation.screens.login.cleanarchitecture.state.UserLoginUiState
import com.rzrasel.wordquiz.presentation.screens.login.emailOrMobileEmptyErrorState
import com.rzrasel.wordquiz.presentation.screens.login.passwordEmptyErrorState
import com.rzrasel.wordquiz.presentation.state.ErrorState
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

class UserLoginViewModel(private val loginUseCase: UserLoginUseCase): ViewModel() {
    private val _uiState = mutableStateOf<UserLoginUiState>(UserLoginUiState.Idle)
    val uiState: State<UserLoginUiState> = _uiState
    var loginState = mutableStateOf(UserLoginState())
        private set
    private val _userModel = MutableLiveData<UserModel>(UserModel(""))
    val userModel: LiveData<UserModel> get() = _userModel

    fun userLogin(email: String? = null, password: String? = null) {
        viewModelScope.launch(Dispatchers.IO) {
            _uiState.value = UserLoginUiState.Loading
            val loginResult = loginUseCase.execute(email, password)
            when(loginResult) {
                is NetworkResult.Loading -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    Log.i("DEBUG_LOG", "Loading Data ${loginResult.data}")
                }
                is NetworkResult.Success -> {
                    //Log.i("DEBUG_LOG", it.data.toString())
                    Log.i("DEBUG_LOG", "Success Data ${loginResult.data}")
                    loginResult.data?.let {
                        _userModel.value?.userAuthToken = it.data?.data?.accessToken
                        _uiState.value = UserLoginUiState.Success(it)
                    } ?: run {
                        _uiState.value = UserLoginUiState.Error("Null data")
                    }
                }
                is NetworkResult.Error -> {
                    /*effects.send(
                        BaseContract.Effect.Error(
                            it.message ?: ErrorsMessage.gotApiCallError
                        )
                    )*/
                    //Log.e("DEBUG_LOG", it.message.toString())
                    Log.e("DEBUG_LOG", "Error Data ${loginResult.data}")
                    loginResult.message?.let {
                        _uiState.value = UserLoginUiState.Error(it)
                    } ?: run {
                        _uiState.value = UserLoginUiState.Error("Unknown error")
                    }
                }
            }
        }
    }

    fun onUiEvent(loginUiEvent: UserLoginUiEvent) {
        when(loginUiEvent) {
            is UserLoginUiEvent.EmailOrMobileChanged -> {
                /*loginState.value = loginState.value.copy(
                    emailOrMobile = loginUiEvent.inputValue
                )*/
                loginState.value = loginState.value.copy(
                    emailOrMobile = loginUiEvent.inputValue,
                    errorState = loginState.value.errorState.copy(
                        emailOrMobileErrorState = if (loginUiEvent.inputValue.trim().isNotEmpty())
                            ErrorState()
                        else
                            emailOrMobileEmptyErrorState
                    )
                )
            }

            is UserLoginUiEvent.PasswordChanged -> {
                loginState.value = loginState.value.copy(
                    password = loginUiEvent.inputValue,
                    errorState = loginState.value.errorState.copy(
                        passwordErrorState = if (loginUiEvent.inputValue.trim().isNotEmpty())
                            ErrorState()
                        else
                            passwordEmptyErrorState
                    )
                )
            }
            UserLoginUiEvent.Submit -> {
                val inputsValidated = validateInputs()
                if(inputsValidated) {
                    userLogin(
                        email = loginState.value.emailOrMobile,
                        password = loginState.value.password
                    )
                }
            }
        }
    }

    private fun validateInputs(): Boolean {
        val emailOrMobileString = loginState.value.emailOrMobile.trim()
        val passwordString = loginState.value.password
        return when {

            // Email/Mobile empty
            emailOrMobileString.isEmpty() -> {
                loginState.value = loginState.value.copy(
                    errorState = UserLoginErrorState(
                        emailOrMobileErrorState = emailOrMobileEmptyErrorState
                    )
                )
                false
            }

            //Password Empty
            passwordString.isEmpty() -> {
                loginState.value = loginState.value.copy(
                    errorState = UserLoginErrorState(
                        passwordErrorState = passwordEmptyErrorState
                    )
                )
                false
            }

            // No errors
            else -> {
                // Set default error state
                loginState.value = loginState.value.copy(errorState = UserLoginErrorState())
                true
            }
        }
    }

    fun onCallSkipped() {
        _uiState.value = UserLoginUiState.Success(
            UserLoginModel(
                "success", "", UserLoginDataModel(
                    false,
                    UserLoginDataItemModel(
                        accessToken = userModel.value?.userAuthToken ?: ""
                    )
                )
            )
        )
    }
}