package com.rzrasel.wordquiz.presentation.screens.dashboard.layout

import androidx.compose.foundation.layout.Column
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import com.rzrasel.wordquiz.presentation.components.components.CustomCheckBox

@Composable
fun DashboardDefaultHeaderLayout(title: String = "") {
    var isChecked by remember { mutableStateOf(false) }

    Column {
        Text(
            text = title,
        )
        CustomCheckBox(
            text = "I agree with the terms & condition I agree with the terms & condition I agree with the terms & condition I agree with the terms & condition I agree with the terms & condition",
            isChecked = isChecked,
            onCheckedChange = {
                isChecked = it
            },
        )
        /*NormalCheckBox(
            modifier = Modifier.fillMaxWidth(),
            label = "Test And assign it to parent with PointerEventPass.Initial with no consume call and will result as",
            state = checkedState,
            onStateChange = {
                checkedState = !checkedState
            }
        )*/
    }
}