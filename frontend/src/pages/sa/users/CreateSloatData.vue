<template>
    <AForm :model="formState" v-bind="layout" name="nest-messages" :validate-messages="validateMessages"
      @finish="finish">
      <AFormItem :name="['user', 'name']" label="Name" :rules="[{ required: true }]">
        <AInput v-model:value="formState.user.name" autocomplete="off" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.name }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'email']" label="Email" :rules="[{ type: 'email' }]">
        <AInput v-model:value="formState.user.email" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.email }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'phone']" label="Phone" :rules="[{ validator: validatePhone }]">
        <AInput type="number" v-model:value="formState.user.phone" @keydown="blockNonNumericKeys"
          @input="castToNumber" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.phone }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'address']" label="Address">
        <AInput v-model:value="formState.user.address" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.address }}</a-label>
      </AFormItem>

      <AFormItem :name="['user', 'password']" label="Password">
        <AInput v-model:value="formState.user.password" />
        <a-label :style="{ color: '#780650' }">{{ fieldErrors.password }}</a-label>
      </AFormItem>

      <AFormItem :wrapper-col="{ ...layout.wrapperCol, offset: 8 }">
        <AButton type="primary" html-type="submit">Submit</AButton>
      </AFormItem>
    </AForm>
</template>
